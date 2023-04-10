@extends('layouts.app')
@inject('pubFunc', 'App\Http\Controllers\pubFunc')
@inject('currentCity', 'App\Http\Controllers\CityController')

@section('comboSearch')
    {{  $catName->title  ??  'همه آگهی ها'  }}
@endsection

@section('title',  '|'. $currentCity::show($citySlug??(Cookie::get('city')??'mashhad'))  )
@section('meta_keywords', '|'. $currentCity::show($citySlug??(Cookie::get('city')??'mashhad')) )
@section('meta_description',  '|'. $currentCity::show($citySlug??(Cookie::get('city')??'mashhad'))  )

@section('content')


    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-3 col-xl-2 d-none  d-lg-block d-xl-block ">
                <div class="  sidebar-item ">
                    <div class="col-12  ">
                        <div class="col-12">
                            دسته بندی ها
                        </div>
                        <div class="list-group mt-4 card border-secondary ">
                            @foreach ($categories as $category)
                                <a href="{{ route('cities.cat.show', ['city' => $citySlug , 'category' => $category->slug])}}"
                                   class="list-group-item ">
                                     <i class="{{ $category->icon ?? '' }}"></i> {{ $category->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">


        @foreach ($root as $cat)
            @if(count($cat->products))
            <div class="border-info border-bottom ">
                <div class="row">
                    <h3 class="col-11">
                        <a href="{{ route('cities.cat.show', ['city' => $citySlug , 'category' => $cat->slug])}}" class="border-3  border-info border-bottom ">
                            <i class="{{ $cat->icon }}"></i> {{ $cat->title }}
                        </a>
                    </h3>
                    <a href="{{ route('cities.cat.show', ['city' => $citySlug , 'category' => $cat->slug])}}" class="col-1 d-none  d-md-block">بیشتر</a>
                </div>
            </div>

            <div class="row mb-2 mt-4">
                <div class="col-xl-4 col-lg-5 col-md-12 col-sm-12  col-xs-12  cat-deal-bigbox">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12  padding-0 mt-1">
                            <a href="{{url('v/'.$cat->products[0]->randId.'.html')}}" class="text-decoration-none text-reset">
                                <div class="card   mb-4 shadow">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-12">
                                            <img
                                                @php
                                                    if(file_exists(public_path($cat->products[0]->resized_name)) && $cat->products[0]->resized_name!= '' ){
                                                              echo 'src="'.url($cat->products[0]->resized_name).'"';
                                                         }else{
                                                              echo 'src="'.url('/images/noimage.png').'"';
                                                         }
                                                @endphp
                                                alt="{{ $cat->products[0]->subject }}"   height="300px" class="w-100 card-img-top">
                                            <div class="card-body d-md-none  d-lg-block">
                                                <div class=" row ">
                                                    <h3 class="card-title">{{ $cat->products[0]->subject }}</h3>
                                                    <p class=" card-text">
                                                        {{$pubFunc::cuttext( $cat->products[0]->description , 200) }}
                                                    </p>
                                                    <div class="row  col-sm-12  justify-content-between">
                                                        <span class="col-sm-12 card-subtitle  text-muted">
                                                            @if($cat->products[0]->price)
                                                                {{ number_format($cat->products[0]->price) }}
                                                                تومان
                                                            @endif
                                                        </span>
                                                        <small class="col-sm-12  card-subtitle  text-muted "> {{ $pubFunc::relativeTime($cat->products[0]->updated_at) }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-12 d-none  d-md-block  d-lg-none">
                                            <div class="card-body  ">
                                                    <h3 class="   card-title">{{ $cat->products[0]->subject }}</h3>
                                                    <p class="card-text">
                                                        {{ $pubFunc::cuttext( $cat->products[0]->description , 200) }}
                                                    </p>
                                                    <div class="row  col-sm-12  justify-content-between">
                                                        <span class="col-sm-12 card-subtitle  text-muted">
                                                            @if($cat->products[0]->price)
                                                                {{ number_format($cat->products[0]->price) }}
                                                                تومان
                                                            @endif</span>
                                                        <small class="col-sm-12  "> {{ $pubFunc::relativeTime($cat->products[0]->updated_at) }}</small>
                                                    </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12  padding-0 mt-1">
                            <div class="card  mb-4 shadow  more1 text-center p-3">
                                بیش از 100 پیشنهاد
                                {{ $cat->title }}
                                <a href="{{ route('cities.cat.show', ['city' => $citySlug , 'category' => $cat->slug])}}" type="button" class="mt-2 text-center btn btn-primary">مشاهده همه</a>
                                </blockquote>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-7 col-md-12 col-sm-12 col-xs-12 cat-deal-smallbox">
                    <div class="row">
            @for ($i = 1; $i < count($cat->products); $i++)
                @if($i < 5)
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6    ">
                            <a href="{{url('v/'.$cat->products[$i]->randId.'.html')}}" class="text-decoration-none text-reset">
                                <div class="card mb-4  shadow ">
                                    <div class="row no-gutters">
                                        <div class="col-6 col-md-6 col-lg-12">
                                            <img
                                                @php
                                                    if(file_exists(public_path($cat->products[$i]->resized_name)) && $cat->products[$i]->resized_name!= '' ){
                                                          echo 'src="'.url($cat->products[$i]->resized_name).'"';
                                                     }else{
                                                          echo 'src="'.url('/images/noimage.png').'"';
                                                     }
                                                @endphp
                                                height="180px" class="w-100 card-img-top"
                                                alt="{{ $cat->products[$i]->subject }}"
                                            >
                                            <div class="card-body  d-none d-lg-block">
                                            <h6 class="card-title">{{ $pubFunc::cuttext($cat->products[$i]->subject ,60) }}</h6>
                                            <div class="row">
                                                    <span class="col-sm-12 card-subtitle  text-muted">
                                                     @if($cat->products[$i]->price)
                                                            {{ number_format($cat->products[$i]->price) }}
                                                            تومان
                                                        @endif
                                                    </span>
                                                    <small class="col-sm-12  "> {{ $pubFunc::relativeTime($cat->products[$i]->updated_at) }}</small>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-6   col-lg-12  d-block  d-sm-block d-md-block d-lg-none">
                                            <div class="card-body  ">
                                                <div class=" row no-gutters  justify-content-between">
                                                    <h6 class="   col-12 card-title">{{ $pubFunc::cuttext($cat->products[$i]->subject , 180) }}</h6>
                                                    <span class=" col-12 card-text  text-muted">
                                                        @if($cat->products[$i]->price)
                                                            {{ number_format($cat->products[$i]->price) }}
                                                            تومان
                                                        @endif
                                                    </span>
                                                    <small class="col-12 card-text text-muted"> {{ $pubFunc::relativeTime($cat->products[$i]->updated_at) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                @elseif($i == 5 || $i == 6)
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6  d-lg-none d-xl-block   ">
                            <a href="{{url('v/'.$cat->products[$i]->randId.'.html')}}" class="text-decoration-none text-reset">
                                <div class="card mb-4  shadow ">
                                    <div class="row no-gutters">
                                        <div class="col-6 col-md-6 col-lg-12">
                                            <img
                                                @php
                                                    if(file_exists(public_path($cat->products[$i]->resized_name)) && $cat->products[$i]->resized_name!= '' ){
                                                          echo 'src="'.url($cat->products[$i]->resized_name).'"';
                                                     }else{
                                                          echo 'src="'.url('/images/noimage.png').'"';
                                                     }
                                                @endphp
                                                height="180px" class="w-100 card-img-top"
                                                alt="{{ $cat->products[$i]->subject }}">
                                            <div class="card-body  d-none d-lg-block">
                                                <h6 class="card-title">{{ $pubFunc::cuttext($cat->products[$i]->subject ,60) }}</h6>
                                                <div class="row">
                                                    <span class="col-sm-12 card-subtitle  text-muted">
                                                     @if($cat->products[$i]->price)
                                                            {{ number_format($cat->products[$i]->price) }}
                                                            تومان
                                                        @endif
                                                    </span>
                                                    <small class="col-sm-12  "> {{ $pubFunc::relativeTime($cat->products[$i]->updated_at) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6   col-lg-12  d-block  d-sm-block d-md-block d-lg-none">
                                            <div class="card-body  ">
                                                <div class=" row no-gutters  justify-content-between">
                                                    <h6 class="   col-12 card-title">{{ $pubFunc::cuttext($cat->products[$i]->subject , 180) }}</h6>
                                                    <span class=" col-12 card-text  text-muted">
                                                    @if($cat->products[$i]->price)
                                                            {{ number_format($cat->products[$i]->price) }}
                                                            تومان
                                                        @endif
                                                    </span>
                                                    <small class="col-12 card-text text-muted"> {{ $pubFunc::relativeTime($cat->products[$i]->updated_at) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                @elseif($i > 6)
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 d-md-none d-lg-none d-xl-block  ">
                            <a href="{{url('v/'.$cat->products[$i]->randId.'.html')}}" class="text-decoration-none text-reset">
                                <div class="card mb-4  shadow ">
                                    <div class="row no-gutters">
                                        <div class="col-6 col-md-6 col-lg-12">
                                            <img
                                                @php
                                                    if(file_exists(public_path($cat->products[$i]->resized_name)) && $cat->products[$i]->resized_name!= '' ){
                                                          echo 'src="'.url($cat->products[$i]->resized_name).'"';
                                                     }else{
                                                          echo 'src="'.url('/images/noimage.png').'"';
                                                     }
                                                @endphp
                                                height="180px" class="w-100 card-img-top"
                                                alt="{{ $cat->products[$i]->subject }}"
                                            >
                                            <div class="card-body  d-none d-lg-block">
                                                <h6 class="card-title">{{ $pubFunc::cuttext($cat->products[$i]->subject ,60) }}</h6>
                                                <div class="row">
                                                    <span class="col-sm-12 card-subtitle  text-muted">
                                                        @if($cat->products[$i]->price)
                                                            {{ number_format($cat->products[$i]->price) }}
                                                            تومان
                                                        @endif
                                                    </span>
                                                    <small class="col-sm-12  "> {{ $pubFunc::relativeTime($cat->products[$i]->updated_at) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6   col-lg-12  d-block  d-sm-block d-md-block d-lg-none">
                                            <div class="card-body  ">
                                                <div class=" row no-gutters  justify-content-between">
                                                    <h6 class="   col-12 card-title">{{ $pubFunc::cuttext($cat->products[$i]->subject , 180) }}</h6>
                                                    <span class=" col-12 card-text  text-muted">
                                                        @if($cat->products[$i]->price)
                                                            {{ number_format($cat->products[$i]->price) }}
                                                            تومان
                                                        @endif
                                                    </span>
                                                    <small class="col-12 card-text text-muted"> {{ $pubFunc::relativeTime($cat->products[$i]->updated_at) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                @endif

            @endfor
                    </div>
                </div>
            </div>
            @endif
        @endforeach
            </div>
         </div>
    </div>

@endsection
