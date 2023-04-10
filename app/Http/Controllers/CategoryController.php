<?php

namespace App\Http\Controllers;
use App\category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * 24-4
    14-6
    15-1
    7-4
    8-6
    18-11
    16-3
    20-7
    21-10
    22-11
    23-8
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->q){
            $categories = category::where('title', 'LIKE' , '%'.$request->q.'%')
                    ->orWhere('slug', 'LIKE' , '%'.$request->q.'%')
                    ->paginate(14)->withPath('?q='.$request->q);
        }else {
            $categories = category::paginate(14);
        }
        return view('layouts.category.index' , compact('request' , 'categories'));
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
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function showTree(Request $request , $root)
    {
        //$categories1 = "";
        $categories = category::where('parent_slug', $root)->with('sub_category')->get();
        foreach ($categories as $category){
            if($category->sub_category->count()){
                //$categories1 = $category->sub_category;
                //$category->sub_category->sub_category = $this->showTree( $request ,$category->sub_category);
                var_dump($category->sub_category);die();
                //$this->showSubCategories($category->sub_category);
                //$categories1 .= response()->json(array("root" => $categories));
            }
        }
        //$categories1 .= response()->json(array("root" => $categories));
        //$data = array("ID"=>-1 , "Name"=> "لیست گروه ها", "ChildData"=>"");
        return  response()->json(array("success1"=>true , "root" => $categories , "param"=>$root));
        //return $categories1;
    }

    public function showSubCategories()
    {

    }
}
