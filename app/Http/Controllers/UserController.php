<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        return view('member.index' , compact('user'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\user  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\user  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\user  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validator($request->all())->validate();
        $user = User::find($id);
        //$user->fill($request);
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->email = $request->email;
        if($request->password){
            $user->password  = Hash::make($request->password);
        }
        $user->save();
        return redirect('/dashboard/profile')->with('success', true)->with('msg', 'اطلاعات با موفقیت بروزرسانی گردید');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\user  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    protected function validator(array $data)
    {
       // var_dump($data);die();
        $check = array('name' => ['required', 'string', 'max:255']);
        if(Auth::user()->email != $data['email'])
            $check['email'] = array('', 'string', 'email', 'max:255', 'unique:users');
        if(Auth::user()->mobile != $data['mobile'])
            $check['mobile'] = array( 'required', 'string', 'min:11', 'unique:users');
        if($data['password'])
            $check['password'] = array( 'required', 'string', 'min:8', 'confirmed');
        return Validator::make($data, $check);
    }


    public function export()
    {
        return Excel::download( User::all(), 'users.xlsx');
    }

    public function import()
    {
        /*https://www.codechief.org/article/import-and-export-csv-file-in-laravel
         * php artisan make:import UsersImport --model=User
         *
         app/Imports/UsersImport.php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new User([
            'name'     => $row['name'],
            'email'    => $row['email'],
            'password' => \Hash::make($row['password']),
        ]);
    }
}
         *
        Excel::import(new UsersImport,request()->file('file'));

        return redirect()->back();
        */
    }
}
