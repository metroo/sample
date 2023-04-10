@extends('layouts.app')
@inject('pubFunc', 'App\Http\Controllers\pubFunc')
@inject('currentCity', 'App\Http\Controllers\CityController')
@section('comboSearch')
    {{  $catName->title  ??  'همه آگهی ها'  }}
@endsection

@section('title',  '|'. $currentCity::show($citySlug??(Cookie::get('city')??'mashhad')) .'|'.$product->subject)
@section('meta_keywords', '|'. $currentCity::show($citySlug??(Cookie::get('city')??'mashhad')) .'|'.$product->subject)
@section('meta_description',  '|'. $currentCity::show($citySlug??(Cookie::get('city')??'mashhad')) .'|'.$product->description)

@section('adminlte_js')
    <script>
        $(document).ready(function(){
            $('#chat_form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{ url('dashboard/mailbox/send_chat_direct') }}/{{ $product->user_id }}/{{$product->id}}",
                    data: { msg : $("#chat_box_message").val()},
                    method:"POST",
                    success:function(data){
                        window.location = '{{ url('dashboard/mailbox')}}'
                    }
                });
            });

            $('#login_form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{ url('ajaxlogin') }}",
                    data: { mobile : $("#mobile").val() , password : $("#password").val()},
                    method:"POST",
                    success:function(data){
                        if(data[0].success)
                            location.reload();
                    }
                });
            });
        });
    </script>
@endsection

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

        <div class="card  shadow mb-2">
            <div class="card-body">
                <div class="row">
                <div class="col  ">
                    <div class="">
                        <h1 >{{ $product->subject }}</h1>
                        <hr>



                        <div class="row ">
                            @if($product->price > 0)
                            <div class="{{ count($uploads)>0?'col-sm-12' :'col-sm-6'   }} row ">
                                <span class="col-sm-6 card-subtitle  lead"> قیمت </span>
                                <small class="col-sm-6 text-md-left lead font-weight-bold"> {{  number_format($product->price)  }} تومان </small>
                            </div>
                            @endif
                            <div class="{{ count($uploads)>0?'col-sm-12' :'col-sm-6'   }} row mt-3">
                                <span class="col-sm-12 card-subtitle  text-muted "> {{ $pubFunc::relativeTime($product->updated_at) }}
                                <button type="button" class="btn btn-outline-primary  btn-sm float-right"
                                        data-toggle="modal" data-target="#modalChatbox" aria-haspopup="true"
                                        aria-expanded="false">چت کنید</button></span>
                            </div>
                        </div>
                        <hr >
                        <div class="row">
                            @foreach ($scdata as $s_data)
                                @if($s_data['key'] ==  "latitude")
                                @elseif($s_data['key'] ==  'longitude')
                                @else
                            <div class=" {{ count($uploads)>0?'col-sm-12' :'col-sm-6'   }} row">
                                <span class="col-sm-6 card-subtitle  lead"> {{$s_data['key']}} </span>
                                <span class="col-sm-6 text-xl-left  lead font-weight-bold"><strong>  {{$s_data['value']}} </strong></span>
                                <hr>
                            </div>
                                @endif
                            @endforeach
                        </div>
                        <hr >
                        <p class="lead">
                            {{ $product->description }}
                        </p>
                    </div>
                </div>
                     @if($uploads->count() > 0)


                    <div class="col-sm-12 col-md-7 col-lg-8 ">
                        <!--Carousel Wrapper-->
                        <div id="carousel-thumb" class="carousel slide carousel-fade carousel-thumbnails" data-ride="carousel">
                            <!--Slides-->
                            <div class="carousel-inner" role="listbox">
                                @php $i=0 @endphp
                                @foreach ($uploads as $upload)
                                    <div style="max-height: 400px" class=" carousel-item   @if($i==0) active @endif @php $i=1 @endphp ">
                                        <img  height="400px" class="w-100  d-block"
                                              @php
                                                  if(file_exists(public_path($upload->resized_name)) && $upload->filename!= '' ){
                                                            echo 'src="'.url($upload->filename).'"';
                                                       }else{
                                                            echo 'src="'.url('/images/noimage.png').'"';
                                                       }
                                              @endphp
                                              alt="{{ $product->subject }}" >

                                    </div>
                                @endforeach
                            </div>
                            <!--/.Slides-->
                            <!--Controls-->
                            <a class="carousel-control-prev" href="#carousel-thumb" role="button" data-slide="prev">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">قبلی</span>
                            </a>
                            <a class="carousel-control-next" href="#carousel-thumb" role="button" data-slide="next">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">بعدی</span>
                            </a>
                            <!--/.Controls-->
                            <ol class="carousel-indicators">
                                @php $i=0; @endphp
                                @foreach ($uploads as $upload)

                                    <li data-target="#carousel-thumb" data-slide-to="{{$i}}" class="
                                    @if ($loop->first)
                                        active
                                    @endif
                                      ">
                                        <img  height="400px" class="w-100  d-block img-fluid"
                                              @php
                                                  if(file_exists(public_path($upload->resized_name))  && $upload->resized_name!= '' ){
                                                            echo 'src="'.url($upload->resized_name).'"';
                                                       }else{
                                                            echo 'src="'.url('/images/noimage.png').'"';
                                                       }
                                              @endphp
                                              alt="{{ $product->subject }}" >

                                    </li>
                                    @php ++$i; @endphp
                                @endforeach
                            </ol>
                        </div>
                        <!--/.Carousel Wrapper-->
                    </div>
                        @else
                    @endif
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="modalChatbox" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title col-xs-1 col-sm-3 col-md-3 col-lg-2" id="exampleModalLongTitle">شروع چت</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body  " >
                    <div class="container">
                        @auth
                        <form action="#" method="post" id="chat_form">
                            @csrf
                            <div class="input-group">
                                <input type="text" id="chat_box_message" name="message" placeholder="نوشتن متن ..." class="form-control">
                                <span class="input-group-append">
                          <button type="submit" class="btn btn-primary">ارسال</button>
                        </span>
                            </div>
                        </form>
                            @endauth
                        @guest
                                <form method="POST" action="#" id="login_form">
                                    @csrf

                                    <div class="form-group row">
                                        <label for="mobile" class="col-md-4 col-form-label text-md-right">{{ __('Mobile') }}</label>

                                        <div class="col-md-6">
                                            <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror"
                                                   name="mobile"  required  >

                                            @error('mobile')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Login') }}
                                            </button>

                                            @if (Route::has('password.request'))
                                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
