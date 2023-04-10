@extends('layouts.master')

@section('classes_body')
    hold-transition sidebar-mini
@stop

@section('navbar')
    @include('admin.navbar')
@stop

@section('sidebar')
    @include('admin.sidebarAdmin' , ['page'=>'categoryIndex'])
@stop


@section('contentHeader')
    @include('admin.contentheader' , ['header' => ' ' ])
@stop


@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">لیست گروه ها</h3>

                            <div class="card-tools">
                                <form  action="{{Url('admin/category/')}}" method="GET" >
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" name="q" class="form-control float-right" placeholder="جستجو" value="{{ Request::get('q') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0" style="height: 300px;">
                            <table class="table table-head-fixed text-nowrap">
                                <thead>
                                <tr>
                                    <th style="width: 10px">شماره</th>
                                    <th style="width: 200px">عملیات</th>
                                    <th>عنوان</th>
                                    <th>نام مستعار</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{$category->id}}</td>
                                        <td>


                                            <form  action="{{Url('admin/category/'.$category->id)}}"
                                                        method="POST"  onsubmit="return confirm('آیا برای حذف اطمینان دارید؟');" >
                                                @csrf
                                                @method('delete')
                                                <a class="btn btn-info btn-sm " href="{{Url('admin/category/'.$category->id.'/edit')}}" title="ویرایش">
                                                    <i class="fas fa-pencil-alt fa-lg">
                                                    </i>
                                                </a>
                                                <a class="btn btn-info btn-sm " href="{{Url('admin/schema/'.$category->id.'/edit')}}" title="ویرایش فرم">
                                                    <i class="fab fa-wpforms fa-lg"></i>
                                                </a>
                                                <button  type="submit" class="delete_button btn btn-danger btn-sm  " >
                                                    <i class="fas fa-trash fa-lg"></i> حذف</button>

                                            </form>

                                        </td>
                                        <td>{{$category->title}}</td>
                                        <td>{{$category->slug}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{$categories->links()}}

                            </ul>
                        </div>

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
