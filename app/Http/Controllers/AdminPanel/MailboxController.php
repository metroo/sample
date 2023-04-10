<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\City;
use App\mailbox;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use Verta;

class MailboxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
     * https://github.com/hekmatinasser/verta
     * show list users
SELECT * from mailboxes where sender = 3 or reciver = 3 group by chat_thread

SELECT * from mailboxes where ( sender = 3 or reciver = 3 ) and chat_thread = '13-1605170375'

show chat box list mesage
SELECT * from mailboxes where (sender = 3 and reciver = 2) or (sender = 2 and reciver = 3)
SELECT bodymessage , sender , reciver from mailboxes where (sender = 3 and reciver = 2) or (sender = 2 and reciver = 3) ORDER BY chat_timestamp

    select * from ( SELECT `name`, bodymessage , chat_timestamp , chat_thread from mailboxes ma, users usr where ( ma.reciver = usr.id ) and  ( sender = 3   ) group by chat_thread
UNION
SELECT  `name`,bodymessage , chat_timestamp , chat_thread from mailboxes ma, users usr where ( ma.sender = usr.id  ) and  ( reciver = 3 ) group by chat_thread )  as som GROUP BY chat_thread
     * state delivery
     *  1- send
     *  2- seen
     *  3- remove
     * */
    public function fetch_user()
    {
        $userId = -1;
        $first = DB::table('mailboxes')
            ->select( 'id' , 'name' , 'bodymessage' , 'chat_timestamp' , 'chat_thread' )
            ->join('users', 'mailboxes.reciver', '=', 'users.id')
            ->where('mailboxes.sender',  $userId)
            ->groupBy('chat_thread');

        $second = DB::table('mailboxes')
            ->select( 'id' , 'name', 'bodymessage' , 'chat_timestamp' , 'chat_thread')
            ->join('users',  'mailboxes.sender', '=', 'users.id')
            ->where('mailboxes.reciver',   $userId)
            ->union($first)
            ->groupBy('chat_thread');

        $mailbox = DB::table( DB::raw("({$second->toSql()}) as sub") )
            ->mergeBindings($second )
            ->groupBy('chat_thread')
            ->get();

        $user_id = Cookie::get('user_chat_id')?Cookie::get('user_chat_id'):-1;
        $html = '';
        foreach ($mailbox as $user) {
             $html .=  "<a href=\"#\"> <li class=\"list-group-item list-group-item-light user_chat  ";
            if($user_id == $user->id ) $html .= " active ";
             $html .= "\" rel=\"{$user->id}\">
                                {$user->name }
                        </li></a>";
        }
        return  ($html);
    }

    public function fetch_user_chat($id)
    {
        Cookie::queue('user_chat_id', $id, 1000);
        /* SELECT * from mailboxes where (sender = 3 and reciver = 2) or (sender = 2 and reciver = 3) ORDER BY chat_timestamp*/
        $userId = -1;
        $timestamp = isset($_POST['timestamp'])?$_POST['timestamp']:'';
        $chat_timestamp = '';
        if($timestamp != '')
             $mailbox = DB::table('mailboxes')
                ->whereRaw('((sender = ? and reciver = ? ) or (sender = ? and reciver = ? ))',[$userId  , $id , $id  , $userId])
                ->where( 'chat_timestamp' , '>' , $timestamp)
                ->orderBy('chat_timestamp')->get();
        else
            $mailbox = DB::table('mailboxes')
                ->whereRaw('(sender = ? and reciver = ? )',[$userId  , $id])
                ->orWhereRaw('(sender = ? and reciver = ? )',[$id  , $userId])
                ->orderBy('chat_timestamp')->get();

        $html = '';
        if($mailbox){
            foreach ($mailbox as $chat) {
                if($chat->sender == $userId) {
                    $html .= '
                        <div class="direct-chat-msg ">
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-timestamp float-left">'.Verta::instance($chat->chat_timestamp).'</span>
                            </div>
                            <div class="direct-chat-text col-9">
                                '.$chat->bodymessage.'
                            </div>
                        </div>
                    ';
                } else {
                     $html .='
                        <div class="direct-chat-msg right">
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-timestamp float-right">'.Verta::instance($chat->chat_timestamp).'</span>
                            </div>
                            <div class="direct-chat-text col-9 float-right">
                                '.$chat->bodymessage.'
                            </div>
                        </div> ';
                }
                $chat_timestamp = $chat->chat_timestamp;
            }

        }
        $chat_timestamp = ($chat_timestamp=="")?$timestamp:$chat_timestamp;
        return   response()->json([$chat_timestamp , $html] , 200) ;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mailbox = mailbox::paginate(14);
        return view('admin.mailbox' , compact('mailbox'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = -1;//Auth::user()->id;
        $rId = Cookie::get('user_chat_id');
        $mailbox = DB::table('mailboxes')
            ->whereRaw('(sender = ? and reciver = ? )',[$userId  , $rId])
            ->orWhereRaw('(sender = ? and reciver = ? )',[$rId  , $userId])
            ->orderByDesc('chat_timestamp')->limit(1)->get();
        $chat_thread = '';
        if(sizeof($mailbox))
            $chat_thread = $mailbox[0]->chat_thread;
        else
            $chat_thread = $userId.'-'.time();

        $mailbox = new mailbox();
        $mailbox->bodymessage = $request->msg;
        $mailbox->sender = $userId;
        $mailbox->reciver = $rId;
        $mailbox->chat_thread = $chat_thread;
        $mailbox->state = 1;
        $mailbox->save();
        return  ($this::fetch_user_chat($rId));
        //return redirect()->back()->with('message', 'IT WORKS!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\mailbox  $mailbox
     * @return \Illuminate\Http\Response
     */
    public function show(mailbox $mailbox)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\mailbox  $mailbox
     * @return \Illuminate\Http\Response
     */
    public function edit(mailbox $mailbox)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\mailbox  $mailbox
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, mailbox $mailbox)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\mailbox  $mailbox
     * @return \Illuminate\Http\Response
     */
    public function destroy(mailbox $mailbox)
    {
        //
    }
}
