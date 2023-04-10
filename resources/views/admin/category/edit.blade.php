@extends('layouts.master')

@section('classes_body')
    hold-transition sidebar-mini
@stop

@section('navbar')
    @include('admin.navbar')
@stop

@section('sidebar')
    @include('layouts.sidebar')
@stop


@section('contentHeader')
    @include('admin.contentheader' , ['header' => 'ویرایش آگهی' ])
@stop


@section('content')

@stop

@section('footer')
    @include('layouts.footer')
@stop

