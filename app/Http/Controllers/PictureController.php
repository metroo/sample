<?php

namespace App\Http\Controllers;

use App\multimedia;
use App\Picture;
use App\tags;
use App\Upload;
use http\Url;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;
use function GuzzleHttp\Psr7\str;

class PictureController extends Controller
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
        $_SESSION['mediaType'] = $_REQUEST['t'];
        $mc = new MultimediaController();
        $tree_categories = $mc->getCategoryTreebyIDs();
        $tree_categories = json_encode($tree_categories);
        $tree_categories = str_replace('sub_category' , 'inc' , $tree_categories);
        $tree_categories = json_decode($tree_categories);


        $tags = tags::query()->where('published' , "1")->select('id' , 'subject')->get();
        return view('admin.picture.create' , compact(    'tags' , 'tree_categories'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        try{
            $picture = new Picture();
            $picture->subject = $request->inputSubject;
            $inputParentid = $request->inputParentid;
            $picture->description = $request->inputFull_title;
            $imagesid = $request->imagesid;
            $imagesid = str_replace("image-sort-","",$imagesid);
            $imagesid = explode(",",$imagesid);
            $picture->published = ($request->inputPublished == "on")?1:0;
            $tag = ($request->input('inputTags'))?$request->input('inputTags'):[];
            $picture->permalink = $request->inputSlug;
            $picture->template = $request->_template;
            $picture->seo_title = $request->inputSeotitle;
            $picture->seo_description = $request->inputSeodescription;
            $picture->save();
            $picture->categories()->sync($inputParentid);
            $picture->uploads()->sync($imagesid);
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
                $picture->tags()->attach($oldTags);
            }
            if(sizeof($newTags)){
                $picture->tags()->saveMany($newTags);
            }
            return redirect('admin/picture/create?t=multimedia')->with('success', 'اطلاعات با موفقیت درج گردید');
        }catch (\Illuminate\Database\QueryException $e) {
            return redirect('admin/picture/create?t=multimedia')->with('danger', 'خطا در درج رخ داده است '.$e->errorInfo[2]);
            //return response()->json(array("success" => false));
        }catch (Exception $e) {
            return redirect('admin/picture/create?t=multimedia')->with('danger', 'خطا در درج رخ داده است');
            //return response()->json(array("success" => false));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function show(Picture $picture)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function edit(Picture $picture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Picture $picture)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function destroy(Picture $picture)
    {
        //
    }
}
