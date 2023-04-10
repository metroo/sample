<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->q){
            /*
             * $first = product::where('subject', 'LIKE' , '%'.$request->q.'%');
            $products = product::where('subject', 'LIKE' , '%'.$request->q.'%')
                ->union($first)
                ->orderByDesc('id')->paginate(14)->withPath('?q='.$request->q);
            */
            $products = product::where('subject', 'LIKE' , '%'.$request->q.'%')
                ->orWhere('description', 'LIKE' , '%'.$request->q.'%')
                ->groupBy('id')
                ->orderByDesc('id')->paginate(10)->withPath('?q='.$request->q);
        }else {
            $products = product::orderByDesc('id')->paginate(10);
        }
        return response()->json($products , 200);
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
