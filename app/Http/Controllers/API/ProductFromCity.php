<?php

namespace App\Http\Controllers\API;

use App\category;
use App\City;
use App\Http\Controllers\Controller;
use App\product;
use App\Upload;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//https://hekmatinasser.github.io/verta/ تقویم فارسی


class ProductFromCity extends Controller
{
    public function ShowCity(Request $request)
    {

        try {
            //dd(Cookie::get('city'));
            $categoryId = 0;
            $citySlug = Cookie::get('city') ? Cookie::get('city') : 'mashhad';
            $citySlug = $request->city ? $request->city : $citySlug;
            session(['citySlug' => $citySlug]);
            if ($citySlug)
                $city = City::where('slug', $citySlug)->first();
            else
                $city = City::where('slug', "mashhad")->first();
            //$city = DB::table("cities")->where("slug" , $request->city)->first();

                $cityId = -1;
                $root = [];
                $i = 0;
                $categories = category::where('parent_slug', 'root')->select('id', 'slug', 'title', 'icon')
                    ->orderBy('ordering')->get();
                foreach ($categories as $cat) {
                    if($request->q){

                    }else {
                        $products = DB::table("products")
                            ->leftJoin('products_to_categories', 'products_to_categories.product_id', '=', 'products.id')
                            ->leftJoin('products_to_uploads', 'products_to_uploads.product_id', '=', 'products.id')
                            ->leftJoin('uploads', 'products_to_uploads.upload_id', '=', 'uploads.id')
                            ->select('products.id', 'products.subject', 'products.price','products.randId', 'products.description', 'products.updated_at',
                                'products_to_categories.category_id', 'uploads.resized_name')
                            ->where(function ($query) {
                                global $request;
                                $query->where('products.subject', 'LIKE', '%' . $request->q . '%')
                                    ->orWhere('products.description', 'LIKE', '%' . $request->q . '%');
                            })
                            ->where('products.city', '=', $cityId)
                            ->where('products_to_categories.category_id', '=', $cat->id)
                            ->where('products.availability', '=', 2)
                            ->groupBy('products.id')
                            ->orderByDesc( 'products.updated_at')->take(7)->get();
                    }
                    $root[$i] = $cat;
                    $root[$i]['products'] = $products;
                    $i++;
                }

                $categoriesList = category::where('parent_slug', 'root')->with('sub_category')->orderBy('ordering')->get();
                //dd($categoriesList);
                //return response()->json(compact('root', 'categories' , 'citySlug' ), 200);
                 return view('admin.product.indexCity', compact('root', 'categories' , 'categoriesList', 'citySlug' ));

            //return response()->json($root, 200);
        } catch (Exception $e) {
            //return response()->json( "eroor" , 400);
            abort(403, 'صفحه مورد نظر وجود ندارد');
        }
    }
/*
        $product = product::find($id);
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


    }
*/
    public function ShowCityCategory(Request $request, $product)
    {
        try {

            $breadcrumb = array();
            $breadcrumbSeo = array();
            $this::getBreadcrumb($request->category , $breadcrumb , $breadcrumbSeo) ;
            $breadcrumb = array_reverse($breadcrumb);
            $breadcrumbSeo = array_reverse($breadcrumbSeo);
            $breadcrumbSeo = implode(" | ",$breadcrumbSeo);
            //dd($breadcrumbSeo);
            $categories = category::where('parent_slug', $request->category)->select('id', 'slug', 'title', 'icon')->get();
            $citySlug = $request->city;
            $categoryId = 0;
            if($request->city)
                $cityId = City::where('slug', $request->city)->first()->id;
            else
                $cityId = City::where('slug', "mashhad")->first()->id;
            if($request->category)
                $catName = category::where('slug', $request->category)->select('id', 'slug', 'title', 'icon')->first();
            //$city = DB::table("cities")->where("slug" , $request->city)->first();
            if($catName){
                $categoryId = $catName->id;
                //echo ($cityId."-".$categoryId."</br>");
                if($request->q){
                    $products =  DB::table("products")
                        ->leftJoin('products_to_categories', 'products_to_categories.product_id', '=', 'products.id')
                        ->leftJoin('products_to_uploads', 'products_to_uploads.product_id', '=', 'products.id')
                        ->leftJoin('uploads', 'products_to_uploads.upload_id', '=', 'uploads.id')
                        ->select('products.id', 'products.subject', 'products.price','products.randId', 'products.description', 'products.updated_at',
                            'products_to_categories.category_id', 'uploads.resized_name')
                        ->where(function($query) {
                            global $request;
                            $query->where('products.subject', 'LIKE' , '%'.$request->q.'%')
                                ->orWhere('products.description', 'LIKE' , '%'.$request->q.'%');
                        })
                        ->where('city', '=' , $cityId)
                        ->where('products.availability', '=', 2)
                        ->where('category_id', '=' , $categoryId)
                        ->groupBy('products.id')
                        ->orderByDesc('products.updated_at')->paginate(16)->withPath('?q='.$request->q) ;
                }else {
                    $products =  DB::table("products")
                        ->leftJoin('products_to_categories', 'products_to_categories.product_id', '=', 'products.id')
                        ->leftJoin('products_to_uploads', 'products_to_uploads.product_id', '=', 'products.id')
                        ->leftJoin('uploads', 'products_to_uploads.upload_id', '=', 'uploads.id')
                        ->select('products.id', 'products.subject',  'products.price','products.randId', 'products.description', 'products.updated_at',
                            'products_to_categories.category_id', 'uploads.resized_name')
                        ->where('products.city', '=' , $cityId)
                        ->where('products.availability', '=', 2)
                        ->where('products_to_categories.category_id', '=' , $categoryId)
                        ->groupBy('products.id')
                        ->orderByDesc('products.updated_at')->paginate(16) ;

                    //dd($products);
                }
                $categoriesList = category::where('parent_slug', 'root')->with('sub_category')->orderBy('ordering')->get();
                //var_dump($products->links());die();
                if($request->page)
                    return view('layouts.product.cityCategory', compact('products' , 'catName' ,'categories' ,'categoriesList' , 'citySlug' , 'breadcrumb' , 'breadcrumbSeo'));
                else
                    return view('layouts.product.indexCityCategory', compact('products' , 'catName' ,'categories' ,'categoriesList' , 'citySlug' , 'breadcrumb' , 'breadcrumbSeo'));
            }else
                abort(403, 'صفحه مورد نظر وجود ندارد');
        }catch (Exception $e){
            abort(403, 'صفحه مورد نظر وجود ندارد');
        }
/*
        $product = product::find($id);
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
        //return view('layouts.product.edit' , compact('product' , 'categories' , 'sc_data' , 'images', 'public_path'));
*/

    }

    public function ShowDetail($productId)
    {
        try {
            $citySlug = session('citySlug', 'mashhad');
			//SELECT * from categories as ca , products_to_categories as pc where ca.id = pc.category_id and pc.product_id = 1
            $product =  DB::table("products")
                ->leftJoin('products_to_categories', 'products_to_categories.product_id', '=', 'products.id')
                ->select('products.id','products.user_id', 'products.subject',  'products.price', 'products.description',
                    'products.updated_at','products.sc_data','products.sc_id','products_to_categories.category_id')
                ->where('products.randId', '=' , $productId)
                ->where('products.availability', '=', 2)
                ->groupBy('products.id')
                ->orderByDesc('products.id')->first();
            //dd($product);
            if($product){
                $uploads = DB::table("uploads")
                ->leftJoin('products_to_uploads', 'products_to_uploads.upload_id', '=', 'uploads.id')
                ->select('uploads.filename' , 'uploads.resized_name' )
                ->where ('products_to_uploads.product_id', $product->id )->get();
                $categoriesList = category::where('parent_slug', 'root')->with('sub_category')->orderBy('ordering')->get();
                $schema_jsons = DB::table("schema_jsons")
                    ->where('id', $product->sc_id)->get()->first();
                $scdata = [];
                $sc_data_decode = json_decode($product->sc_data , true);
                if(strlen($sc_data_decode['category']) > 1){
                    $breadcrumb = DB::table("categories")
                        ->leftJoin('products_to_categories', 'products_to_categories.category_id', '=', 'categories.id')
                        ->select('categories.slug' , 'categories.title' )
                        ->whereIn ('categories.id', explode(',' ,$sc_data_decode['category']))
                        ->groupBy('categories.id')->get();
                    $breadcrumb = $this::object_to_array($breadcrumb);
                }else {
                    $breadcrumb = DB::table("categories")
                        ->leftJoin('products_to_categories', 'products_to_categories.category_id', '=', 'categories.id')
                        ->select('categories.slug' , 'categories.title' )
                        ->where('products_to_categories.product_id', $product->id)->get();
                    $breadcrumb = $this::object_to_array($breadcrumb);
                }
                //dd($sc_data_decode['category']);
                $this::getProperty($sc_data_decode , json_decode($schema_jsons->sc_json) , $scdata);
                //dd($sc_data);
                return view('layouts.product.indexCityProduct', compact('product' , 'uploads','categoriesList', 'citySlug' , 'scdata' , 'breadcrumb' ));
                //return response()->json($uploads , 200);
            }else
                abort(403, 'صفحه مورد نظر وجود ندارد');
        }catch (Exception $e){
            abort(403, 'صفحه مورد نظر وجود ندارد');
        }
    }

    public function getBreadcrumb($slug , &$a , &$b){
        $categories = category::where('slug', $slug)->select('id', 'slug', 'title', 'icon' , 'parent_slug')->first();
        $a[] = array('slug'=>$categories->slug , 'title'=> $categories->title);
        $b[] = $categories->title ;
        if($categories->parent_slug != 'root')
            $this::getBreadcrumb($categories->parent_slug , $a , $b);
        //return $a;
    }

    public function object_to_array($data)
    {
        if (is_array($data) || is_object($data))
        {
            $result = array();
            foreach ($data as $key => $value)
            {
                $result[$key] = $this::object_to_array($value);
            }
            return $result;
        }
        return $data;
    }

    public function getProperty($jsonObject , $jsonSchema , & $d)
    {
        //dd($jsonSchema);
        $notshow = array("rent_to_single" ,"images" , "title" , "hide_phone" ,"chat_enabled" , "category" , "city", "description","new_price","new_rent" ,
            "neighborhood" , "destination_latitude" , "destination_longitude" , "post_type" , "merchandise_type");
        $numberField = array("new_rent" , "new_credit" , "new_price"  );
        $schema = $jsonSchema->json_schema->properties;
        $i=0;
        foreach($jsonObject as $name => $value){
            if(!in_array($name , $notshow))
            if(isset( $schema->$name)){
                if(is_array($jsonObject[$name])) {
                    if (count($jsonObject[$name])) {
                        foreach ($jsonObject[$name] as $name2 => $value2) {
                            //dd($name . " ---- " . $value);

                            if (!in_array($name2, $notshow))
                                if (isset($schema->$name->properties->$name2)) {
                                    //if($name2 == "latitude")

                                    $p2 = $schema->$name->properties->$name2;
                                   // dd($p2->title);
                                    if (isset($p2->title)) {
                                        $value2 = ($value2 === false) ? "خیر" : $value2;
                                        $value2 = ($value2 === true) ? "بلی" : $value2;
                                        $value2 = ($value2 == "false") ? "خیر" : $value2;
                                        $value2 = ($value2 == "true") ? "بلی" : $value2;
                                        $d[$i]['key'] = $p2->title;
                                        if(in_array($name2 ,$numberField))
                                            $value2 = number_format($value2);
                                        $d[$i]['value'] = $value2;$i++;
                                    }
                                    if($name2 == "latitude"){
                                        $d[$i]['key'] = 'latitude';
                                        $d[$i]['value'] = $value2;$i++;
                                    }
                                    if($name2 ==  "longitude") {
                                        $d[$i]['key'] = 'longitude';
                                        $d[$i]['value'] = $value2;$i++;
                                    }
                                }
                        }
                    }
                }
                else {
                    if(isset( $schema->$name->title)) {
                        $value = ($value===false)?"خیر":$value;
                        $value = ($value===true)?"بلی":$value;
                        $value = ($value == "false") ? "خیر" : $value;
                        $value = ($value == "true") ? "بلی" : $value;
                        $d[$i]['key'] = $schema->$name->title;
                        if(in_array($name ,$numberField))
                            $value = number_format($value);
                        $d[$i]['value'] = $value;$i++;
                    }
                }
            }
        }
        //dd($d);
    }

    public function search(Request $request)
    {
        try {
            //dd($request->search);
            $searchText = $request->search;
            if($request->category != 'all') {
                $breadcrumb = array();
                $products = '';
                $this::getBreadcrumb($request->category, $breadcrumb);
                $breadcrumb = array_reverse($breadcrumb);
                //var_dump($request->search);
                //var_dump($breadcrumb[0]['slug']);die();
                $categories = category::where('parent_slug', $request->category)->select('id', 'slug', 'title', 'icon')->get();
                $citySlug = $request->city;
                $categoryId = 0;
                if ($request->city)
                    $cityId = City::where('slug', $request->city)->first()->id;
                else
                    $cityId = City::where('slug', "mashhad")->first()->id;
                if ($request->category)
                    $catName = category::where('slug', $request->category)->select('id', 'slug', 'title', 'icon')->first();
                //$city = DB::table("cities")->where("slug" , $request->city)->first();
                if ($catName) {
                    $categoryId = $catName->id;
                    //echo ($cityId."-".$categoryId."</br>");
                    if ($request->search) {
                        $products = DB::table("products")
                            ->leftJoin('products_to_categories', 'products_to_categories.product_id', '=', 'products.id')
                            ->leftJoin('products_to_uploads', 'products_to_uploads.product_id', '=', 'products.id')
                            ->leftJoin('uploads', 'products_to_uploads.upload_id', '=', 'uploads.id')
                            ->select('products.id', 'products.subject', 'products.price', 'products.randId', 'products.description', 'products.updated_at',
                                'products_to_categories.category_id', 'uploads.resized_name')
                            ->where(function ($query) {
                                global $request;
                                $query->where('products.subject', 'LIKE', '%' . $request->search . '%')
                                    ->orWhere('products.description', 'LIKE', '%' . $request->search . '%');
                            })
                            ->where('city', '=', $cityId)
                            ->where('category_id', '=', $categoryId)
                            ->groupBy('products.id')
                            ->orderByDesc('products.id')->paginate(16)->withPath('?search=' . $request->search);
                    }else {
                        $products = DB::table("products")
                            ->leftJoin('products_to_categories', 'products_to_categories.product_id', '=', 'products.id')
                            ->leftJoin('products_to_uploads', 'products_to_uploads.product_id', '=', 'products.id')
                            ->leftJoin('uploads', 'products_to_uploads.upload_id', '=', 'uploads.id')
                            ->select('products.id', 'products.subject', 'products.price', 'products.randId', 'products.description', 'products.updated_at',
                                'products_to_categories.category_id', 'uploads.resized_name')

                            ->where('city', '=', $cityId)
                            ->where('category_id', '=', $categoryId)
                            ->groupBy('products.id')
                            ->orderByDesc('products.id')->paginate(16)->withPath('?search=' . $request->search);
                    }
                    $categoriesList = category::where('parent_slug', 'root')->with('sub_category')->orderBy('ordering')->get();
                    //var_dump($breadcrumb[0]['slug']);die();
                    if($request->page)
                        return view('layouts.product.search', compact('searchText' ,'products', 'catName', 'categories', 'categoriesList', 'citySlug', 'breadcrumb'));
                    else
                        return view('layouts.product.indexSearch', compact('searchText' ,'products', 'catName', 'categories', 'categoriesList', 'citySlug', 'breadcrumb'));
                } else
                    abort(403, 'صفحه مورد نظر وجود ندارد');
            }else {
                $categoryId = 0;$products='';
                $citySlug = $request->city ? $request->city : 'mashhad';
                session(['citySlug' => $citySlug]);
                if ($citySlug)
                    $city = City::where('slug', $citySlug)->first();
                else
                    $city = City::where('slug', "mashhad")->first();
                //$city = DB::table("cities")->where("slug" , $request->city)->first();
                if ($city) {
                    $cityId = $city->id;
                    $categories = category::where('parent_slug', 'root')->select('id', 'slug', 'title', 'icon')
                        ->orderBy('ordering')->get();
                    if($request->search){
                        $products = DB::table("products")
                            ->leftJoin('products_to_categories', 'products_to_categories.product_id', '=', 'products.id')
                            ->leftJoin('products_to_uploads', 'products_to_uploads.product_id', '=', 'products.id')
                            ->leftJoin('uploads', 'products_to_uploads.upload_id', '=', 'uploads.id')
                            ->select('products.id', 'products.subject', 'products.price','products.randId', 'products.description', 'products.updated_at',
                                'products_to_categories.category_id', 'uploads.resized_name')
                            ->where(function ($query) {
                                global $request;
                                $query->where('products.subject', 'LIKE', '%' . $request->search . '%')
                                    ->orWhere('products.description', 'LIKE', '%' . $request->search . '%');
                            })
                            ->where('products.city', '=', $cityId)
                            ->groupBy('products.id')
                            ->orderByDesc('products_to_categories.category_id', 'products.id')->paginate(16) ;
                    }else{
                        $products = DB::table("products")
                            ->leftJoin('products_to_categories', 'products_to_categories.product_id', '=', 'products.id')
                            ->leftJoin('products_to_uploads', 'products_to_uploads.product_id', '=', 'products.id')
                            ->leftJoin('uploads', 'products_to_uploads.upload_id', '=', 'uploads.id')
                            ->select('products.id', 'products.subject', 'products.price','products.randId', 'products.description', 'products.updated_at',
                                'products_to_categories.category_id', 'uploads.resized_name')
                            ->where('products.city', '=', $cityId)
                            ->groupBy('products.id')
                            ->orderByDesc('products_to_categories.category_id', 'products.id')->paginate(16) ;
                    }
                    $categoriesList = category::where('parent_slug', 'root')->with('sub_category')->orderBy('ordering')->get();
                    // dd($root);
                    //return response()->json(compact('root', 'categories' , 'citySlug' ), 200);
                    if($request->page)
                        return view('layouts.product.search', compact('searchText' ,'products', 'categories' , 'categoriesList', 'citySlug' ));
                    else
                        return view('layouts.product.indexSearch', compact('searchText' ,'products', 'categories' , 'categoriesList', 'citySlug' ));
                } else {
                    abort(403, 'صفحه مورد نظر وجود ندارد');
                }
            }
        } catch (Exception $e) {
            abort(403, 'صفحه مورد نظر وجود ندارد');
        }
    }

}
