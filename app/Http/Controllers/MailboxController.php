<?php

namespace App\Http\Controllers;

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

    /*
     *
     select * from (
 select `id` ,`product_id`, `name` , `bodymessage` , `chat_timestamp` , `chat_thread` from `mailboxes` inner join `users` on `mailboxes`.`sender` = `users`.`id` where `mailboxes`.`reciver` = 3 group by `chat_thread`
 union
 select `id` ,`product_id`, `name` , `bodymessage` , `chat_timestamp` , `chat_thread` from `mailboxes` inner join `users` on `mailboxes`.`reciver` = `users`.`id` where `mailboxes`.`sender` = 3 group by `chat_thread`
 ) AS a LEFT JOIN products as b on a.product_id = b.id  GROUP BY `chat_thread`


 SELECT * from mailboxes where ( (sender = 3 and reciver = -1) or (sender = -1 and reciver = 3) )
and chat_timestamp >  "2020-11-18 15:13:00"
ORDER BY chat_timestamp

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
    public function fetch_user($rId = -1)
    {
        $userId = Auth::user()->id;
        $first = DB::table('mailboxes')
            ->select( 'id' ,'product_id' , 'name' , 'bodymessage' , 'chat_timestamp' , 'chat_thread' )
            ->join('users', 'mailboxes.reciver', '=', 'users.id')
            ->where('mailboxes.sender',  $userId)
            ->groupBy('chat_thread');

        $second = DB::table('mailboxes')
            ->select( 'id' ,'product_id' , 'name', 'bodymessage' , 'chat_timestamp' , 'chat_thread')
            ->join('users',  'mailboxes.sender', '=', 'users.id')
            ->where('mailboxes.reciver',   $userId)
            ->union($first)
            ->groupBy('chat_thread');

        $mailbox = DB::table( DB::raw(" ({$second->toSql()}) as sub") )
            ->mergeBindings($second )
            ->leftJoin('products', 'product_id', '=', 'products.id')
            ->select('sub.id' , 'product_id' , 'name', 'bodymessage' , 'chat_timestamp' , 'chat_thread' ,'products.subject')
            ->groupBy('chat_thread')
             ->get();
        //dd($mailbox->toSql());
        //$user_id = Cookie::get('user_chat_id')?Cookie::get('user_chat_id'):-1;
        $html = '<a href="#"><li class="list-group-item list-group-item-light user_chat ';
        if($rId == -1) $html .= " active ";
        $html .= '" rel="-1">
                            <i class="nav-icon  fas fa-user-shield " ></i> پستچی متــرو
                        </li></a>';
        foreach ($mailbox as $user) {
            $subject = pubFunc::cuttext($user->subject,45);
             $html .=  "<a href=\"#\"> <li class=\"list-group-item list-group-item-light user_chat  ";
            if($rId == $user->chat_thread ) $html .= " active ";
             $html .= "\" rel=\"{$user->chat_thread}\">
                                {$user->name }
                                <br><span class='small '>{$subject }</span>
                        </li></a>";
        }
        return  ($html);
    }

    public function fetch_user_chat($rId)
    {
        //Cookie::queue('user_chat_id', $id, 1000);
        /* SELECT * from mailboxes where (sender = 3 and reciver = 2) or (sender = 2 and reciver = 3) ORDER BY chat_timestamp*/
        $userId = Auth::user()->id;
        $timestamp = isset($_POST['timestamp'])?$_POST['timestamp']:'';
        $chat_timestamp = '';
        if($rId == -1){
            if ($timestamp != '')
                $mailbox = DB::table('mailboxes')
                    ->whereRaw('((sender = ? and reciver = ?) or (sender = ? and reciver = ?))', [$userId, $rId , $rId, $userId])
                    ->where('chat_timestamp', '>', $timestamp)
                    ->orderBy('chat_timestamp')->get();
            else
                $mailbox = DB::table('mailboxes')
                    ->whereRaw('((sender = ? and reciver = ?) or (sender = ? and reciver = ?))', [$userId, $rId , $rId, $userId])
                    ->orderBy('chat_timestamp')->get();
        } else {
            if ($timestamp != '')
                $mailbox = DB::table('mailboxes')
                    ->where('chat_thread', $rId)
                    ->where('chat_timestamp', '>', $timestamp)
                    ->orderBy('chat_timestamp')->get();
            else
                $mailbox = DB::table('mailboxes')
                    ->where('chat_thread', $rId)
                    ->orderBy('chat_timestamp')->get();
        }
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
        return view('layouts.mailbox' , compact('mailbox'));
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
     *   $rId  => reciver_id
     *   $pId  =>  product_id
     */
    public static function store(Request $request , $rId , $pId = -1)
    {
        $user = $request->user();
        if($user->hasRole('developer'))
            $userId = -1;
        else
            $userId = Auth::user()->id;
        //$rId = Cookie::get('user_chat_id');
        if($pId == -1) {
            $mailbox = DB::table('mailboxes')
                ->whereRaw('(sender = ? and reciver = ? ) or (sender = ? and reciver = ? ) ', [$userId, $rId, $rId, $userId])
                ->orderByDesc('chat_timestamp')->limit(1)->get();
        }else {
            $mailbox = DB::table('mailboxes')
                ->whereRaw('((sender = ? and reciver = ? ) or (sender = ? and reciver = ? )) and product_id = ? ', [$userId, $rId, $rId, $userId , $pId])
                ->orderByDesc('chat_timestamp')->limit(1)->get();
        }
        $chat_thread = '';
        if(sizeof($mailbox))
            $chat_thread = $mailbox[0]->chat_thread;
        else
            $chat_thread = $userId.'-'.time();

        $mailbox = new mailbox();
        $mailbox->bodymessage = $request->msg;
        $mailbox->sender = $userId;
        $mailbox->reciver = $rId;
        $mailbox->product_id = $pId;
        $mailbox->chat_thread = $chat_thread;
        $mailbox->state = 1;
        $mailbox->save();
        if($user->hasRole('developer'))
            return redirect()->back()->with('success', 'IT WORKS!');
        else
            return  (MailboxController::fetch_user_chat($rId));
    }

    public static function storeDirect(Request $request , $rId , $pId = -1)
    {
        $user = $request->user();
        if($user->hasRole('developer'))
            $userId = -1;
        else
            $userId = Auth::user()->id;
        //$rId = Cookie::get('user_chat_id');
        if($pId == -1) {
            $mailbox = DB::table('mailboxes')
                ->whereRaw('(sender = ? and reciver = ? ) or (sender = ? and reciver = ? ) ', [$userId, $rId, $rId, $userId])
                ->orderByDesc('chat_timestamp')->limit(1)->get();
        }else {
            $mailbox = DB::table('mailboxes')
                ->whereRaw('((sender = ? and reciver = ? ) or (sender = ? and reciver = ? )) and product_id = ? ', [$userId, $rId, $rId, $userId , $pId])
                ->orderByDesc('chat_timestamp')->limit(1)->get();
        }
        $chat_thread = '';
        if(sizeof($mailbox))
            $chat_thread = $mailbox[0]->chat_thread;
        else
            $chat_thread = $userId.'-'.time();

        $mailbox = new mailbox();
        $mailbox->bodymessage = $request->msg;
        $mailbox->sender = $userId;
        $mailbox->reciver = $rId;
        $mailbox->product_id = $pId;
        $mailbox->chat_thread = $chat_thread;
        $mailbox->state = 1;
        $mailbox->save();
        return redirect()->back()->with('success', 'IT WORKS!');
    }

    public function storeChatThread(Request $request , $thread)
    {
        $userId = Auth::user()->id;
        if($thread == -1) {
            $findThread = DB::table('mailboxes')
                ->whereRaw('(sender = ? and reciver = ? ) or (sender = ? and reciver = ? ) ', [$userId, -1, -1, $userId])
                ->orderByDesc('chat_timestamp')->limit(1)->get();
            if(sizeof($findThread))
                $thread = $findThread[0]->chat_thread;
            else
                $thread = $userId.'-'.time();
                $mailbox = new mailbox();
                $mailbox->bodymessage = $request->msg;
                $mailbox->sender = $userId;
                $mailbox->reciver = -1;
                $mailbox->product_id = -1;
                $mailbox->chat_thread = $thread;
                $mailbox->state = 1;
                $mailbox->save();
        }else {
            $mailboxRead = DB::table('mailboxes')
                ->where('chat_thread', $thread)
                ->orderByDesc('chat_timestamp')->limit(1)->get();
            //dd($mailboxRead[0]->reciver);
            if (sizeof($mailboxRead)) {
                $mailbox = new mailbox();
                $mailbox->bodymessage = $request->msg;
                $mailbox->sender = $userId;
                $mailbox->reciver = $mailboxRead[0]->reciver;
                $mailbox->product_id = $mailboxRead[0]->product_id;
                $mailbox->chat_thread = $thread;
                $mailbox->state = 1;
                $mailbox->save();
            }
        }
        return  ($this::fetch_user_chat($thread));
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
