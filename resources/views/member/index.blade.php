@extends('layouts.master')

@section('classes_body')
    hold-transition sidebar-mini
@stop

@section('navbar')
    @include('admin.navbar')
@stop

@section('sidebar')
    @include('layouts.sidebar' , ['page'=>'profile'])
@stop


@section('contentHeader')
    @include('admin.contentheader' , ['header' => ' ' ])
@stop


@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">

                    @if(session()->get('success')==true)

                        <div class="alert alert-success">
                            {{ session()->get('msg') }}
                        </div>
                    @elseif(session()->get('success')===false)
                        <div class="alert alert-danger">
                            {{ session()->get('msg') }}
                        </div>
                    @endif
                </div>

                <div class="col-12">


                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">ویرایش پروفایل</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <form method="POST" action="profile/{{$user->id}}" action="{{ route('register') }}">
                                {{csrf_field()}}
                                {{method_field('put')}}

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required autocomplete="name" autofocus>

                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Mobile') }}</label>

                                    <div class="col-md-6">
                                        <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{  $user->mobile  }}" required autocomplete="name" autofocus>

                                        @error('mobile')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email  }}" required autocomplete="email">

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"   autocomplete="new-password">

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"   autocomplete="new-password">
                                    </div>
                                </div>
                                <!--
                                <div class="form-group row">
                                    <div class=" btn btn-default btn-file">
                                        <i class="fas fa-paperclip"></i>  تصویر پروفایل
                                        <input type="file" name="attachment">
                                    </div>
                                    <p class="help-block"> Max. 1MB </p>
                                </div>
                                -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="float-right">
                                <button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> ارسال</button>
                            </div>
                            <button type="reset" class="btn btn-default"><i class="fas fa-times"></i> پاک کردن</button>
                        </div>
                        <!-- /.card-footer -->
                        </form>
                    </div>
                    <!-- /.card -->

                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@stop

@section('footer')
    @include('layouts.footer')
@stop
