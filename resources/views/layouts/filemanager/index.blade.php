@extends('layouts.master')

@section('classes_body')
    hold-transition sidebar-mini
@endsection

@section('navbar')
    @include('admin.navbar')
@endsection

@section('sidebar')
    @include('admin.sidebarAdmin', ['page'=>'filemanager'])
@endsection

@section('contentHeader')
    @include('admin.contentheader' , ['header' =>'' ])
@endsection


@section('content')
    <div class="container-fluid">
        <div class="col-md-12" style=" height: 80vh;  ">
            <iframe src="{{url('/filemanager')}}" class="embed-responsive-item w-100 h-100" frameborder="0" ></iframe>
        </div>
    </div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

@section('adminlte_js')
    <script>

    </script>
@endsection
