<?php

namespace App\Http\Controllers;

use App\Config_type;
use App\sound;
use App\tags;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class SoundController extends Controller
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
        return view('admin.sound.create' , compact(    'tags' , 'tree_categories' , 'sound_type', 'monody_name'));

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
            $sound = new sound();
            $sound->subject = $request->inputSubject;
            $sound->description = $request->inputDescription;
            $inputParentid = $request->inputParentid;
            $sound->duration = $request->inputDuration;
            $sound->sound_id = $request->soundFile_formFieldId;
            $sound->image_id = $request->soundImage_formFieldId;

            if(is_numeric($request->inputSoundtype_id)) {
                $sound->soundtype_id = $request->inputSoundtype_id;
            }else{
                if(strlen($request->inputSoundtype_id)>2) {
                    $config_type = new Config_type();
                    $config_type->name = $request->inputSoundtype_id;
                    $config_type->gname = "sound_type";
                    $config_type->save();
                    $sound->soundtype_id = $config_type->id;
                }
            }

            if(is_numeric($request->inputMonodyname_id)) {
                $sound->monodyname_id = $request->inputMonodyname_id;
            }else {
                if(strlen($request->inputMonodyname_id) > 2) {
                    $config_type2 = new Config_type();
                    $config_type2->name = $request->inputMonodyname_id;
                    $config_type2->slug = $request->inputMonodynameSlug;
                    $config_type2->gname = "monody_name";
                    $config_type2->published = 1;
                    $config_type2->save();
                    $sound->monodyname_id = $config_type2->id;
                }
            }

            $sound->published = ($request->inputPublished == "on")?1:0;
            $tag = ($request->input('inputTags'))?$request->input('inputTags'):[];
            $sound->permalink = $request->inputSlug;
            $sound->template = $request->_template;
            $sound->seo_title = $request->inputSeotitle;
            $sound->seo_description = $request->inputSeodescription;
            $sound->save();
            $sound->categories()->sync($inputParentid);

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
                $sound->tags()->attach($oldTags);
            }
            if(sizeof($newTags)){
                $sound->tags()->saveMany($newTags);
            }
            return redirect('admin/sound/create?t=multimedia')->with('success', 'اطلاعات با موفقیت درج گردید');
        }catch (\Illuminate\Database\QueryException $e) {
            return redirect('admin/sound/create?t=multimedia')->with('danger', 'خطا در درج رخ داده است '.$e->errorInfo[2]);
            //return response()->json(array("success" => false));
        }catch (Exception $e) {
            return redirect('admin/sound/create?t=multimedia')->with('danger', 'خطا در درج رخ داده است'.$e->getMessage());
            //return response()->json(array("success" => false));
        }catch (\Exception $e) {
            return redirect('admin/sound/create?t=multimedia')->with('danger', 'خطا در درج رخ داده است'.$e->getMessage());
            //return response()->json(array("success" => false));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\sound  $sound
     * @return \Illuminate\Http\Response
     */
    public function show(sound $sound)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\sound  $sound
     * @return \Illuminate\Http\Response
     */
    public function edit(sound $sound)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\sound  $sound
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, sound $sound)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\sound  $sound
     * @return \Illuminate\Http\Response
     */
    public function destroy(sound $sound)
    {
        //
    }
}
