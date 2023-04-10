@extends('layouts.master')

@section('classes_body')
    hold-transition sidebar-mini
@stop

@section('navbar')
    @include('admin.navbar')
@stop

@section('sidebar')
    @include('admin.sidebarAdmin')
@stop


@section('contentHeader')
    @include('admin.contentheader')
@stop


@section('content')
    @include('layouts.samplecontent')
@stop

@section('footer')
    @include('layouts.footer')
@stop
