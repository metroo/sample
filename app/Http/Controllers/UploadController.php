<?php

namespace App\Http\Controllers;

use App\tags;
use App\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

/*
 * برای تغییر اندازه تصاویر و ایجاد آیکون، از کتابخانه intervention image استفاده خواهیم کرد. دستور زیر را اجرا کنید تا کتابخانه intervention را از طریق composer آپلود شود:
composer require intervention/image 2.4
شما همچنین خط زیر را در قسمت provides در فایل config/app.php وارد می کنیم:
Intervention\Image\ImageServiceProvider::class,
همچنین در آرایه aliases خط زیر را وارد می کنیم:
'Image' => Intervention\Image\Facades\Image::class,
 *
 * */
class UploadController extends Controller
{
    private $photos_path;
    private $userfiles;

    public function __construct()
    {
        $this->userfiles = '/userfiles';
        $this->photos_path = public_path($this->userfiles);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $file_type = isset($_REQUEST['file_type']) ? $_REQUEST['file_type'] : 1;
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : '';
        $original_name = isset($_REQUEST['original_name']) ? $_REQUEST['original_name'] : '';
        $limit = isset($_REQUEST['pageSize']) ? $_REQUEST['pageSize'] : 5;
        $offset = isset($_REQUEST['pageIndex']) ? $_REQUEST['pageIndex']-1 : 0;
        $order = isset($_REQUEST['sortField']) ? $_REQUEST['sortField'] : 'id';
        $dir = isset($_REQUEST['sortOrder']) ? $_REQUEST['sortOrder'] : 'desc';
        $limit += 0;
        $offset += 0;
        $offset *= $limit;

        $validator = Validator::make(['limit' => $limit , 'offset' => $offset   ], [
            'limit' => 'required|numeric' ,
            'offset' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            abort(404);
        }else {
            $next = Url('admin/pictureList') . '?offset='.($offset+$limit).'&limit='.$limit;
            $previous = Url('admin/pictureList') . '?offset='.($offset-$limit).'&limit='.$limit;
            if(strlen($id)==0 && strlen($subject)==0 && strlen($original_name)==0 ) {
                $count = Upload::where('file_type', '=' , $file_type)->count();
                $result = Upload::query()->select('id', 'subject', 'original_name' , 'filename' , 'file_type' ,'upload_type' )
                    ->where('file_type', '=' , $file_type)
                    ->orderBy($order , $dir)->offset($offset)->limit($limit)->get();
            }else{
                $countQuery = Upload::query();
                if(strlen($original_name))
                    $countQuery->where('original_name', 'LIKE' , '%'.$original_name.'%');
                if(strlen($subject))
                    $countQuery->where('subject', 'LIKE' , '%'.$subject.'%');
                if(strlen($id))
                    $countQuery->where('id', 'LIKE' , '%'.$id.'%');
                $count = $countQuery->where('file_type', '=' , $file_type)->count();

                $resultQuery = Upload::query();
                if(strlen($original_name))
                    $resultQuery->where('original_name', 'LIKE' , '%'.$original_name.'%');
                if(strlen($subject))
                    $resultQuery->where('subject', 'LIKE' , '%'.$subject.'%');
                if(strlen($id))
                    $resultQuery->where('id', 'LIKE' , '%'.$id.'%');

                $result = $resultQuery->where('file_type', '=' , $file_type)->
                select('id', 'subject', 'original_name' ,'filename' ,  'file_type' , 'upload_type')->
                orderBy($order , $dir)->offset($offset)->limit($limit)->get();
            }
            return response()->json(array('url'=>Url('') ,'count' => $count,  'results' => $result));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];
        $allowedImageMimeTypes = ['image/jpeg','image/gif','image/png','image/bmp','image/svg+xml'];
        $allowedSoundMimeTypes = ['audio/mpeg','audio/mp4','application/octet-stream'];
        $allowedVideoMimeTypes = ['video/mp4','video/x-matroska'];
        //dd($request->file_title.$request->inputTitle);
        $photos = $request->file('file');

        try {
            $Mimetype = $request->file('file')->getMimeType();
        } catch (\Exception $e) {
            $Mimetype = $request->file('file')->getClientMimeType();
        }
        //dd($Mimetype);
        if (!is_array($photos)) {
            $photos = [$photos];
        }

        if (!is_dir($this->photos_path)) {
            mkdir($this->photos_path, 0777);
        }
        $path = $this->photos_path. '/' .date('Ymd');
        if (!is_dir($path)) {
            mkdir($path, 0777);
        }
        $ids = [];
        $dateUrl = $this->userfiles.'/'.date('Ymd');
        for ($i = 0; $i < count($photos); $i++) {
            $photo = $photos[$i];
            $name = sha1(date('YmdHis') . Str::random(30));
            $save_name = $name . '.' . $photo->getClientOriginalExtension();
            $resize_name = $name . Str::random(2) . '.' . $photo->getClientOriginalExtension();

            if(in_array($Mimetype , $allowedImageMimeTypes)) {
                $fileType = 1;
                Image::make($photo)
                    ->resize(500, null, function ($constraints) {
                        $constraints->aspectRatio();
                    })
                    ->save($path . '/' . $resize_name);
                $resize_name = $dateUrl.'/'.$resize_name;
            }else if(in_array($Mimetype , $allowedSoundMimeTypes)){
                $fileType = 2;
                $resize_name = '';
            }else if(in_array($Mimetype , $allowedVideoMimeTypes)){
                $fileType = 3;
                $resize_name = '';
            }else {
                $fileType = 0;
                $resize_name = '';
            }
            $photo->move($path, $save_name);
            $pathtofile =  $dateUrl.'/'.$save_name ;
            $upload = new Upload();
            $upload->upload_type = 1;
            $upload->file_type = $fileType;
            $upload->subject = $request->inputTitle;
            $upload->filename = $dateUrl.'/'.$save_name;
            $upload->resized_name =  $resize_name;
            $upload->original_name = basename($photo->getClientOriginalName());
            $upload->save();
            $ids[] =  array("filename"=>$upload->original_name,"id"=>$upload->id);
        }
        return Response::json([
            'message' => 'File saved Successfully' ,
            'ids' => $ids ,
            'path' => $pathtofile
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Upload $upload
     * @return \Illuminate\Http\Response
     *
     * // Path to the project's root folder
    * echo base_path();
 *
* // Path to the 'app' folder
    * echo app_path();
 *
* // Path to the 'public' folder
    * echo public_path();
 *
* // Path to the 'storage' folder
    * echo storage_path();
 *
* // Path to the 'storage/app' folder
    * echo storage_path('app');
     */
    public function show($id , $size = "thumbnail")
    {
        $upload = Upload::find($id);
        switch ($size){
            case "original" :
                $pathToFile = $upload->filename;
                break;
            case "thumbnail" :
                $pathToFile = $upload->resized_name;
                break;
            default:
                $pathToFile = $upload->resized_name;
            break;
        }
        return response()->file(public_path().$pathToFile);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function edit(Upload $upload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Upload $upload)
    {
        $uploaded_image = Upload::find($request->id);
        $uploaded_image->subject = $request->subject;
        $uploaded_image->original_name = $request->original_name;
        $uploaded_image->save();
        return Response::json($uploaded_image,  200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $filename = $request->id;
        $uploaded_image = Upload::find($filename);//where('original_name', basename($filename))->first();

        if (empty($uploaded_image)) {
            return Response::json(['message' => 'Sorry file does not exist'], 400);
        }

        if($uploaded_image->upload_type == 1) {
            $file_path = public_path($uploaded_image->filename);
            $resized_file = public_path($uploaded_image->resized_name);

            if (file_exists($file_path)) {
                unlink($file_path);
            }

            if (file_exists($resized_file)) {
                unlink($resized_file);
            }
        }

        if (!empty($uploaded_image)) {
            $uploaded_image->delete();
        }

        return Response::json(['message' => 'File successfully delete' , "p"=>"$file_path" , "r"=>"$resized_file"], 200);
    }
}
