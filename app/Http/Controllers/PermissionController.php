<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /*https://www.codechief.org/article/user-roles-and-permissions-tutorial-in-laravel-without-packages
    $user = $request->user(); //getting the current logged in user
    dd($user->hasRole('admin','editor')); // and so on

    @role('developer')
        Hello developer
    @elserole
    @endrole

    @can('edit-users') canEdit @endcan
    @can('create-tasks') canCreat @endcan

    //dd($user->can('create-tasks'));
    //dd($user->givePermissionsTo('create-tasks'));
    //dd($user->hasRole('developer'));
    //dd($user->can('permission-slug'));
    //dd($request->user()->hasRole('admin','editor'));
   */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
