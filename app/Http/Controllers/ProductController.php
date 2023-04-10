<?php

namespace App\Http\Controllers;

use App\category;
use App\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *     *
    update products
    set sc_data =
    {    "category": "6,48", "location": {"city": 347 }   }
     *
     * @if($product->availability == 1)
    <span class="small alert alert-warning" role="alert">
    در حال بررسی
    </span>
    @elseif($product->availability == 2)
    <span class="small alert alert-success" role="alert">
    منتشر شده
    </span>
    @elseif($product->availability == 3)
    <span class="small alert alert-secondary" role="alert">
    در انتظار پرداخت
    </span>
    @elseif($product->availability == 4)
    <span class="small alert alert-danger" role="alert">
    عدم تایید
    </span>
    @endif
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if($user->hasRole('developer')){
            if ($request->q) {
                $products = product::where(function ($query) {
                        global $request;
                        $query->where('subject', 'LIKE', '%' . $request->q . '%')
                            ->orWhere('description', 'LIKE', '%' . $request->q . '%')
                            ->orWhere('randId', '=',  $request->q )
                            ->orWhere('id', '=',  $request->q );
                    })
                    ->orderBy('availability')->paginate(14)->withPath('?q=' . $request->q);
            } else {
                $products = product::orderBy('availability')->paginate(14);
            }
        }else {
            if ($request->q) {
                $products = product::where('user_id', Auth::user()->id)
                    ->where(function ($query) {
                        global $request;
                        $query->where('subject', 'LIKE', '%' . $request->q . '%')
                            ->orWhere('description', 'LIKE', '%' . $request->q . '%')
                            ->orWhere('randId', '=',  $request->q )
                            ->orWhere('id', '=',  $request->q );
                    })
                    ->orderByDesc('id')->paginate(14)->withPath('?q=' . $request->q);
            } else {
                $products = product::where('user_id', Auth::user()->id)->orderByDesc('id')->paginate(14);
            }
        }
        //$products = product::paginate(14);
        return view('layouts.product.index' , compact('request' ,'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$categories = categories::with('subcategory');
        $categories = category::where('parent_slug', 'root')->with('sub_category')->get();
        //$categories = categories::with('sub_category')->get();
        //$categories = categories::all()->where('parent_slug', 'root')->;
        return view('layouts.product.create' , compact('categories'));
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
        //var_dump($request->sc_data['new_price']);
        try {
            $uploadsId = $request->images;//DB::table('uploads')->whereIn("original_name",$request->images)->pluck('id')->toArray();;
            $catIds = $request->categories;
            $product = new product();
            $product->user_id = Auth::user()->id;
            $product->subject = $request->sc_data['title'];
            $product->description = $request->sc_data['description'];
            $product->city = $request->sc_data['location']['city'];
            $product->sc_id = $request->sc_id;
            $product->sc_data = json_encode($request->sc_data);
            $product->published = 1;
            $product->availability = 1;
            if (array_key_exists("new_price",$request->sc_data))
                $product->price = $request->sc_data['new_price'];
            elseif (array_key_exists("new_rent",$request->sc_data))
                $product->price = $request->sc_data['new_rent'];
            //var_dump($request->sc_data);
            $product->save();
            $product->randId = pubFunc::randId($product->id);
            $product->save();
            $product->categories()->sync($catIds);
            $product->uploads()->sync($uploadsId);
            //session(['success' => 'Contact deleted!']);
            $request->session()->flash('success', 'اطلاعات با موفقیت درج گردید');
            return  response()->json(array("success"=>true));
        }catch (Exception $e) {
            return response()->json(array("success" => false));
        }
        //return redirect('/dashboard/product')->with('success', 'Contact deleted!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $product = product::find($id);
        //$this->authorize('update', $product);
        $categories = category::where('parent_slug', 'root')->with('sub_category')->get();
        //$images = DB::table("products_to_uploads")->where("product_id" , $id)->get();
        $images = DB::table('products_to_uploads')
            ->leftJoin('uploads', 'products_to_uploads.upload_id', '=', 'uploads.id')
            ->where("products_to_uploads.product_id" , $id)->get();
        //var_dump($product);die();
        $public_path = public_path();
        $sc_data = json_decode($product->sc_data);
        //$categories = categories::with('sub_category')->get();
        //$categories = categories::all()->where('parent_slug', 'root')->;
        return view('layouts.product.edit' , compact('product' , 'categories' , 'sc_data' , 'images', 'public_path'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $uploadsId = $request->images;//DB::table('uploads')->whereIn("original_name",$request->images)->pluck('id')->toArray();;
            $catIds = $request->categories;
            $product = product::find($id);
            $product->user_id = Auth::user()->id;
            $product->subject = $request->sc_data['title'];
            $product->description = $request->sc_data['description'];
            $product->city = $request->sc_data['location']['city'];
            $product->sc_id = $request->sc_id;
            $product->sc_data = json_encode($request->sc_data);
            $product->published = 1;
            $product->availability = 1;
            if (array_key_exists("new_price",$request->sc_data))
                $product->price = $request->sc_data['new_price'];
            elseif (array_key_exists("new_rent",$request->sc_data))
                $product->price = $request->sc_data['new_rent'];
            //var_dump($request->sc_data);
            $product->save();
            $product->categories()->sync($catIds);
            $product->uploads()->sync($uploadsId);
            //session(['success' => 'Contact deleted!']);
            $request->session()->flash('success', 'اطلاعات با موفقیت بروزرسانی گردید');
            return  response()->json(array("success"=>true));
        }catch (Exception $e) {
            return response()->json(array("success" => false));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        product::find($id)->delete();
        DB::table("products_to_categories")->where("product_id" , $id)->delete();
        DB::table("products_to_uploads")->where("product_id" , $id)->delete();
        return redirect('/dashboard/product')->with('success', 'اطلاعات با موفقیت حذف گردید');
        //return back();
    }

    public function destroyFile(Request $request , $id)
    {
        //var_dump($id);die();
        DB::table('products_to_uploads')->where("product_id" , $id)->where("upload_id" ,  $request->id)->delete();
        return  response()->json(array("success"=>true));
    }

    public function approve(Request $request, $id)
    {
        try {
            $product = product::find($id);
            $product->availability = 2;
            $product->save();
            //session(['success' => 'Contact deleted!']);
            $request->session()->flash('success', 'اطلاعات با موفقیت بروزرسانی گردید');
            return redirect('/admin/product')->with('success', 'اطلاعات با موفقیت بروزرسانی گردید');
            //return  response()->json(array("success"=>true));
        }catch (Exception $e) {
            $request->session()->flash('danger', 'بروزرسانی با مشکل مواجه شد');
            return redirect('/admin/product')->with('danger', 'بروزرسانی با مشکل مواجه شد');
            //return response()->json(array("success" => false));
        }
    }

    public function notapprove(Request $request, $id)
    {
        try {

            //dd($request->msg);
            $product = product::find($id);
            $product->availability = 4;
            $product->save();
            MailboxController::store($request , $product->user_id);
            //session(['success' => 'Contact deleted!']);
            $request->session()->flash('success', 'اطلاعات با موفقیت بروزرسانی گردید');
            //return redirect('/admin/product')->with('success', 'اطلاعات با موفقیت بروزرسانی گردید');
            return  response()->json(array("success"=>true , "msg" => $request->msg));
        }catch (Exception $e) {
            $request->session()->flash('danger', 'بروزرسانی با مشکل مواجه شد');
            //return redirect('/admin/product')->with('danger', 'بروزرسانی با مشکل مواجه شد');
            return response()->json(array("success" => false , "msg" => $request->msg));
        }
    }


    public function updateShortLink()
    {
        /*
         * {"title": "نمونه اگهی مترو", "contact": {"phone": "09355619001", "chat_enabled": true},
         *  "category": "7,190", "location": {"city": 347}, "new_price": 10000,
         * "description": "جزئیات و نکات قابل توجه آگهی خود را کامل و دقیق بنویسید. درج شماره موبایل در متن آگهی مجاز نیست ."}
         * */
        //$pr =  product::leftJoin('products_to_categories', 'products.id', '=', 'products_to_categories.product_id')->get();
        $pr = product::all();
        //$product = '';
        foreach ($pr as $pw){
            /*$product = json_encode(array("title"=>$pw->subject,"location"=>array("city" => $pw->city) ,  "category"=>"",
                "new_price"=> $pw->price ,  "description"=>$pw->description  ));
            $pw->sc_data = $product;*/
            $pw->sc_id = 51;
            $pw->save();
            //dd($pw);
        }
        dd("end");
    }
}
