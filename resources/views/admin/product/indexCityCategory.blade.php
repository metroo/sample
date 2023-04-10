@extends('layouts.app')
@inject('pubFunc', 'App\Http\Controllers\pubFunc')
@inject('currentCity', 'App\Http\Controllers\CityController')

@section('comboSearch')
    {{  $catName->title  ??  'همه آگهی ها'  }}
@endsection
@section('title',  ' | '. $currentCity::show($citySlug??(Cookie::get('city')??'mashhad')) .' | '.$breadcrumbSeo)
@section('meta_keywords',  ' | '. $currentCity::show($citySlug??(Cookie::get('city')??'mashhad')) .' | '.$breadcrumbSeo )
@section('meta_description',  ' | '. $currentCity::show($citySlug??(Cookie::get('city')??'mashhad')) .' | '.$breadcrumbSeo )

@section('content')

    <div class="container-fluid">
        <nav aria-label="breadcrumb alert  ">
            <ol class="breadcrumb alert alert-light">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}">بازگشت</a></li>
                @foreach ($breadcrumb as $bread)
                    <li class="breadcrumb-item " aria-current="page">
                        <a href="{{ route('cities.cat.show', ['city' => $citySlug , 'category' => $bread['slug']])}}">{{ $bread['title'] }}</a>
                    </li>
                @endforeach
            </ol>
        </nav>


        <div class="row  no-gutters">
            <div class="col-lg-3 col-xl-2 d-none  d-lg-block d-xl-block">
                @include('admin.product.sidebar', ['categories' => $categories , 'citySlug' => $citySlug  ])
            </div>
            <div class="col no-gutters">
                <div class="   col-12">
                   <div class="border-info border-bottom ">

                        <h3 class=" col-11">
                            <a href="#" class="border-3  border-info border-bottom ">
                                <i class="{{ $catName->icon }}"></i> {{ $catName->title }}
                            </a>
                        </h3>
                    </div>
                </div>
                <div class="col-12 mb-2 mt-4">
                    <div class="row" id="productList">
                        @forelse  ($products as $product)
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6    ">
                                <a href="{{url('v/'.$product->randId.'.html')}}" class="text-decoration-none text-reset">
                                    <div class="card mb-4  shadow ">
                                        <div class=" row no-gutters">
                                            <div class="col-6 col-md-6 col-lg-12 ">
                                                <img  height="180px" class="w-100 card-img-top"
                                                  @php
                                                      if(file_exists(public_path($product->resized_name)) && $product->resized_name!= '' ){
                                                                echo 'src="'.url($product->resized_name).'"';
                                                           }else{
                                                                echo 'src="'.url('/images/noimage.png').'"';
                                                           }
                                                  @endphp
                                                 alt="{{ $product->subject }}" >
                                                <div class="card-body d-none d-lg-block  ">
                                                    <h6 class="card-title">{{ $pubFunc::cuttext($product->subject ,60) }}</h6>
                                                    <div class="row">
                                                        <span class="col-sm-12 card-subtitle  text-muted">
                                                        @if($product->price)
                                                                {{ number_format($product->price) }}
                                                                تومان
                                                            @endif
                                                        </span>
                                                        <small class="col-sm-12  "> {{ $pubFunc::relativeTime($product->updated_at) }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6   col-lg-12  d-block  d-sm-block d-md-block d-lg-none">
                                                <div class=" card-body ">
                                                    <div class=" row  no-gutters justify-content-between">
                                                        <h6 class="  col-12 card-title">{{ $pubFunc::cuttext( $product->subject ,180) }}</h6>
                                                        <span class=" col-12 card-text  text-muted">
                                                            @if($product->price)
                                                                {{ number_format($product->price) }}
                                                                تومان
                                                            @endif
                                                        </span>
                                                        <small class="col-12 card-text text-muted"> {{ $pubFunc::relativeTime($product->updated_at) }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="col-12    ">
                                <div class="alert alert-primary col-12" role="alert">
                                 موردی یافت نشد
                                 </div>
                            </div>
                        @endforelse

                    </div>
                    @if(!$products->isEmpty() and count($products)>15)
                        <div class="alert alert-primary" id="seeMore"> در حال دریافت اطلاعات ...</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@section('adminlte_js')
    <script>
        curent_url="{{Request::url()}}";
        var counter=2;
        var productDataLength = true;
        //console.log("{{Request::url()}}"  , "asd");
        $(window).scroll(function () {
            //console.log($(window).scrollTop() , $(document).height() - $(window).height() - 5 )
            if (($(window).scrollTop() > $(document).height() - $(window).height() - 1  ) && productDataLength ) {
                //console.log('scrolll append data')
                appendData();
            }
        });
        function appendData() {
            //console.log('append data')
           $.get(curent_url+"?page="+counter++, function( data ) {
          }).done(function(data) {
               //counter++;
               if(data.length <200) {
                   productDataLength = false;
                   var $seeMore = $("#seeMore");
                   $seeMore.hide();
               } else
                $('#productList').append(data);
          })
          .fail(function() {
              alert( "خطا در دریافت داده ، تلاش مجدد !" );
          });
        }
    </script>
@endsection

