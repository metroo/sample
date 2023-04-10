<?php

namespace App\Http\Controllers;

use App\category;
use App\multimedia;
use App\taggables;
use App\tags;
use App\Upload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class MultimediaController extends Controller
{
    public function getCategoryTreeIDs($catID = 0) {
        $row = category::query()->
        selectRaw("id")->
        where('parent_id', $catID)->where('menu_type' , 4)->get();
        $row = json_decode($row);
        $mer =[];
        $mer1 =[];
        foreach ($row as $ro) {
            $mer1[] = $ro->id;
            $p = $this->getCategoryTreeIDs($ro->id);
            $mer = array_merge($p ,$mer);
        }
        return array_merge($mer1, $mer);
    }
    public function getCategoryTreebyIDs($catID = 0 , $menu_type = 4) {
        $row = category::query()->
        selectRaw("IF(id = 0 ,'#' ,id) as id ,slug , IF(parent_id = 0 ,'#' ,parent_id) as parent , title as text ")->
        where('parent_id', $catID)->where('menu_type' , $menu_type)->get();
        $row = json_decode($row);
        foreach ($row as $ro) {
            $ro->inc = $this->getCategoryTreebyIDs($ro->id);
        }
        if($catID == 0) {
            $p = json_decode(json_encode(array(array("id" => 0, "slug" => "", "parent" => "#", "text" => "ریشه"))));
            return array_merge($p , $row);
        }
        return $row;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
/*
        $key = "TEMPLATE_NAME" ;$value = "\Illuminate\Http";
        pubFunc::putPermanentEnv($key ,$value);*/

        $tree_categories = $this->getCategoryTreebyIDs();
        //dd($tree_categories);
        $tree_categories = json_encode($tree_categories);
        $tree_categories = str_replace('sub_category' , 'inc' , $tree_categories);
        $tree_categories = json_decode($tree_categories);

        $categories = category::query()->where('menu_type', '4')->
        selectRaw("IF(id = 0 ,'#' ,id) as ids ,slug , IF(parent_id = 0 ,'#' ,parent_id) as parent , title as text ")->get(); ;
        $category = json_encode($categories);
        $category = str_replace('"slug"' , ' "state" : { "opened" : true } , "slug"' , $category);
        $category = str_replace('"ids":"' , '"id":"tree' , $category);
        $category = str_replace('"parent":"' , '"parent":"tree' , $category);
        $category = str_replace('"parent":"tree#' , '"parent":"#' , $category);
        $category = json_decode($category);

        $tags = tags::query()->where('published' , "1")->select('id' , 'subject')->get();

        //$categories  = [];
        return view('admin.multimedia.index' , compact('request' , 'categories' , 'category' , 'tags' , 'tree_categories'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd('asdf');
        return view('admin.multimedia.createSound');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSound()
    {
        dd('asdf');
        return view('admin.multimedia.createSound');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //inputTitle=&inputSlug=&inputFull_title=&inputOrdering=&inputPublished=on&inputSeotitle=&inputSeodescription=
        try{
            $category = new category();
            $category->title = $request->inputSubject;
            $category->parent_id = $request->inputParent_id;
            $category->slug = $request->inputSlug;
            $category->full_title = $request->inputFull_title;
            $category->ordering = $request->inputOrdering;
            $category->published = ($request->inputPublished == "on")?1:0;
            $category->category_type = ($request->inputCategory_type == "on")?3:2;
            $tag = ($request->input('inputTags'))?$request->input('inputTags'):[];
            $category->logo = $request->categoryImage_formFieldId;
            $category->banner = $request->categoryBanner_formFieldId;
            $category->seo_title = $request->inputSeotitle;
            $category->seo_description = $request->inputSeodescription;
            $category->menu_type = 4;
            $category->template = $request->_template;
            $category->save();

            $oldTags =[];
            $newTags =[];
            if(sizeof($tag))
            foreach($tag as $t){
                if(is_numeric($t)){
                    $tag1  = tags::find($t);
                    $oldTags[] = $tag1->id;
                }else{
                    $tag2 = new tags();
                    $tag2->subject = $t;
                    $tag2->published = 1;
                    $newTags[] = $tag2;
                }
            }
            if(sizeof($oldTags)){
                $category->tags()->attach($oldTags);
            }
            if(sizeof($newTags)){
                $category->tags()->saveMany($newTags);
            }

            return redirect('admin/multimedia/categories')->with('success', 'اطلاعات با موفقیت درج گردید');
            //$request->session()->flash('success', 'اطلاعات با موفقیت درج گردید');
            //return  response()->json(array("success"=>true));
        }catch (\Illuminate\Database\QueryException $e) {
             return redirect('admin/multimedia/categories')->with('danger', 'خطا در درج رخ داده است '.$e->errorInfo[2]);
            //return response()->json(array("success" => false));
        }catch (Exception $e) {
            return redirect('admin/multimedia/categories')->with('danger', 'خطا در درج رخ داده است');
            //return response()->json(array("success" => false));
        }

        //return redirect('admin/multimedia/categories')->with('danger', 'Contact deleted!');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\multimedia  $multimedia
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        switch ($request->func){
            case "updateparent":
                if($this->updateParent($request)){
                    $success = true;
                    return response()->json(compact('success'  ), 200);
                }else{
                    $success = false;
                    return response()->json(compact('success'  ), 200);
                }
                break;
            case "renamenode":
                if($this->renameNode($request)){
                    $success = true;
                    return response()->json(compact('success'  ), 200);
                }else{
                    $success = false;
                    return response()->json(compact('success'  ), 200);
                }
                break;
            case "deletenode":
                if($this->destroy($request)){
                    $success = true;
                    return response()->json(compact('success'  ), 200);
                }else{
                    $success = false;
                    return response()->json(compact('success'  ), 200);
                }
                break;
        }


    }

    public function updateParent(Request $request)
    {
        try{
            $cat = category::find($request->id);
            $cat->parent_id = $request->parentid;
            $cat->save();
            return  true;
        }catch (\Exception $exception){
            return false;
        }
    }
    public function renameNode(Request $request)
    {
        try{
            $cat = category::find($request->id);
            $cat->title =  $request->newname;
            $cat->save();
            return  true;
        }catch (\Exception $exception){
            //dd($exception->errorInfo[2]);
            return false;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\multimedia  $multimedia
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request , $id)
    {
        $id = str_replace("tree" , "", $id );
        $cat = category::find($id);
        $tags =[];$logo = '' ;$banner = '';
        if($cat) {
            $success = "true";
            $alltags = $cat->tags;
            foreach ($alltags as $tag){
                $tags[] = $tag->id;
            }
            $logo = Upload::query()->find($cat->logo);
            $banner = Upload::query()->find($cat->banner);

        }
        else
             $success = "false";
        return response()->json(compact('success' , 'cat' , 'tags' , 'logo' , 'banner' ), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\multimedia  $multimedia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         //inputTitle=&inputSlug=&inputFull_title=&inputOrdering=&inputPublished=on&inputSeotitle=&inputSeodescription=
        try{
            $category = category::find($id);
            $category->title = $request->inputSubject;
            $category->parent_id = $request->inputParent_id;
            $category->slug = $request->inputSlug;
            $category->full_title = $request->inputFull_title;
            $category->ordering = $request->inputOrdering;
            $category->published = ($request->inputPublished == "on")?1:0;
            $category->category_type = ($request->inputCategory_type == "on")?3:2;
            $tag = ($request->input('inputTags'))?$request->input('inputTags'):[];
            $category->logo = $request->categoryImage_formFieldId;
            $category->banner = $request->categoryBanner_formFieldId;
            $category->seo_title = $request->inputSeotitle;
            $category->seo_description = $request->inputSeodescription;
            $category->menu_type = 4;
            $category->template = $request->_template;
            $category->save();
            $tagId = [];
            foreach ($category->tags as $tag1){
                $tagId[] = $tag1->id;
            }

            $tr = taggables::where('taggable_type' , 'App\category')->where('taggable_id' , $id)->whereIn('tags_id',$tagId)->delete();
            //dd($tr);
            //$tr->delete();
            $oldTags =[];
            $newTags =[];
            if(sizeof($tag))
                foreach($tag as $t){
                    if(is_numeric($t)){
                        $tag1  = tags::find($t);
                        $oldTags[] = $tag1->id;
                    }else{
                        $tag2 = new tags();
                        $tag2->subject = $t;
                        $tag2->published = 1;
                        $newTags[] = $tag2;
                    }
                }
            if(sizeof($oldTags)){
                $category->tags()->attach($oldTags);
            }
            if(sizeof($newTags)){
                $category->tags()->saveMany($newTags);
            }

            return redirect('admin/multimedia/categories')->with('success', 'بروزرسانی اطلاعات با موفقیت انجام گردید');
            //$request->session()->flash('success', 'اطلاعات با موفقیت درج گردید');
            //return  response()->json(array("success"=>true));
        }catch (\Illuminate\Database\QueryException $e) {
            return redirect('admin/multimedia/categories')->with('danger', 'خطا در بروزرسانی رخ داده است '.$e->errorInfo[2]);
            //return response()->json(array("success" => false));
        }catch (Exception $e) {
            return redirect('admin/multimedia/categories')->with('danger', 'خطا در بروزرسانی رخ داده است');
            //return response()->json(array("success" => false));
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\multimedia  $multimedia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $delArray = [];
        $delArray = $this->getCategoryTreeIDs($request->id);
        $delArray[] = $request->id;
        $cat = category::query()->whereIn('id' , $delArray)->delete();
        if($cat)
            return true;
        else
            return false;
       //category::find($request->id)->delete();
        /*DB::table("products_to_categories")->where("product_id" , $id)->delete();
        DB::table("products_to_uploads")->where("product_id" , $id)->delete();*/
    }
}
