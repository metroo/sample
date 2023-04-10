<?php

namespace App\Http\Controllers;

use App\category;
use App\SchemaJson;
use Illuminate\Http\Request;

class SchemaJsonController extends Controller
{
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
     * @param  \App\schema_json  $schema_json
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $schemaJson = SchemaJson::all()->where("cat_id" , $id)->first();
        return response()->json(array("sc_json"=>json_decode($schemaJson['sc_json'])));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\schema_json  $schema_json
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $schemaJson = SchemaJson::all()->where("cat_id" , $id)->first();
        return view('layouts.schema.edit' , compact('schemaJson'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\schema_json  $schema_json
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //UPDATE schema_jsons set sc_json = replace(sc_json,'دیوار','مترو')
        $schemaJson = SchemaJson::all()->where("cat_id" , $id)->first();
        $schemaJson->sc_json = $request->sc_json;
        $schemaJson->save();
        return redirect()->action('CategoryController@index' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\schema_json  $schema_json
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
