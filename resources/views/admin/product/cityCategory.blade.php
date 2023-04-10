@inject('pubFunc', 'App\Http\Controllers\pubFunc')
@forelse  ($products as $product)
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6    ">
        <a href="{{url('v/'.$product->randId.'.html')}}" class="text-decoration-none text-reset">
            <div class="card mb-4  shadow ">
                <div class=" row no-gutters">
                    <div class="col-6 col-md-6 col-lg-12 ">
                        <img  height="180px" class="w-100 card-img-top"
                              @php
                                  if(file_exists(public_path($product->resized_name))  && $product->resized_name!= '' ){
                                            echo 'src="'.url($product->resized_name).'"';
                                       }else{
                                            echo 'src="'.url('/images/noimage.png').'"';
                                       }
                              @endphp
                              alt="{{ $product->subject }}" >

                        <div class="card-body d-none d-lg-block  ">
                            <h6 class="card-title">{{ $pubFunc::cuttext($product->subject ,60) }}</h6>
                            <div class="row">
                                <span class="col-sm-6 card-subtitle  text-muted">
                                @if($product->price)
                                        {{ number_format($product->price) }}
                                        تومان
                                    @endif
                                </span>
                                <small class="col-sm-6 text-md-left text-left"> {{ $pubFunc::relativeTime($product->updated_at) }}</small>
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
    <div class="alert alert-primary col-12" role="alert">
        موردی یافت نشد
    </div>
@endforelse
