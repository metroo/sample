<?php

namespace App\Http\Controllers;

use App\Config_type;
use App\tags;
use App\video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class VideoController extends Controller
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

        $sound_type = DB::table("config_type")->where("gname" , "sound_type")->get();
        $monody_name = DB::table("config_type")->where("gname" , "monody_name")->get();
        $tags = tags::query()->where('published' , "1")->select('id' , 'subject')->get();
        return view('admin.video.create' , compact(    'tags' , 'tree_categories' , 'sound_type', 'monody_name'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            //dd($request);
            if($request->inputSubject == null)
                throw new \Exception(" لطفا اطلاعات فرم را تکمیل نمایید");
            $video = new video();
            $video->subject = $request->inputSubject;
            $video->description = $request->inputDescription;
            $inputParentid = $request->inputParentid;
            $video->duration = $request->inputDuration;
            $video->video_id = $request->videoFile_formFieldId;
            $video->image_id = $request->videoImage_formFieldId;

            if(is_numeric($request->inputSoundtype_id)) {
                $video->soundtype_id = $request->inputSoundtype_id;
            }else{
                if(strlen($request->inputSoundtype_id)>2) {
                    $config_type = new Config_type();
                    $config_type->name = $request->inputSoundtype_id;
                    $config_type->gname = "sound_type";
                    $config_type->save();
                    $video->soundtype_id = $config_type->id;
                }
            }
            if(is_numeric($request->inputMonodyname_id)) {
                $video->monodyname_id = $request->inputMonodyname_id;
            }else {
                if(strlen($request->inputMonodyname_id) > 2) {
                    $config_type2 = new Config_type();
                    $config_type2->name = $request->inputMonodyname_id;
                    $config_type2->slug = $request->inputMonodynameSlug;
                    $config_type2->gname = "monody_name";
                    $config_type2->published = 1;
                    $config_type2->save();
                    $video->monodyname_id = $config_type2->id;
                }
            }

            $video->published = ($request->inputPublished == "on")?1:0;
            $tag = ($request->input('inputTags'))?$request->input('inputTags'):[];
            $video->permalink = $request->inputSlug;
            $video->template = $request->_template;
            $video->seo_title = $request->inputSeotitle;
            $video->seo_description = $request->inputSeodescription;
            $video->save();
            $video->categories()->sync($inputParentid);

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
                $video->tags()->attach($oldTags);
            }
            if(sizeof($newTags)){
                $video->tags()->saveMany($newTags);
            }
            return redirect('admin/video/create?t=multimedia')->with('success', 'اطلاعات با موفقیت درج گردید');
        }catch (\Illuminate\Database\QueryException $e) {
            return redirect('admin/video/create?t=multimedia')->with('danger', 'خطا در درج رخ داده است '.$e->errorInfo[2]);
            //return response()->json(array("success" => false));
        }catch (Exception $e) {
            return redirect('admin/video/create?t=multimedia')->with('danger', 'خطا در درج رخ داده است'.$e->getMessage());
            //return response()->json(array("success" => false));
        }catch (\Exception $e) {
            return redirect('admin/video/create?t=multimedia')->with('danger', 'خطا در درج رخ داده است'.$e->getMessage());
            //return response()->json(array("success" => false));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(video $video)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, video $video)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(video $video)
    {
        //
    }
}
