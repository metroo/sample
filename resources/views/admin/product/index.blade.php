@extends('admin.master')

@section('classes_body')
    hold-transition sidebar-mini
@stop

@section('navbar')
    @include('admin.navbar')
@stop

@section('sidebar')
    @role('developer')
        @include('admin.sidebarAdmin' , ['page'=>'productIndex'])
    @else
        @include('layouts.sidebar' , ['page'=>'productIndex'])
    @endrole
@stop


@section('contentHeader')
    @include('admin.contentheader' , ['header' => ' ' ])
@stop

@section('adminlte_js')
    <script>
        chatIdProduct = -1;
        $(document).ready(function(){

            $('.chatIdProduct').on('click', function () {
                chatIdProduct = $(this).attr("rel");
            });
            $('#chat_form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{ url('admin/product') }}/"+chatIdProduct+"/notapprove",
                    data: { msg : $("#chat_box_message").val()},
                    method:"POST",
                    success:function(data){
                        if(data.success)
                            location.reload()
                    }
                });
            });
        });
    </script>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">

                    @if(session()->get('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    @if(session()->get('danger'))
                        <div class="alert alert-danger">
                            {{ session()->get('danger') }}
                        </div>
                    @endif
                </div>

                <div class="col-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">لیست آگهی ها</h3>

                            <div class="card-tools">

                                <form  action="{{Url('admin/product/')}}" method="GET" >
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
                                    @role('developer')
                                    <th style="width: 210px">عملیات</th>
                                    @else
                                    <th style="width: 100px">عملیات</th>
                                    @endrole
                                    <th>عنوان</th>
                                    <th>وضعیت</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{$product->id}}</td>
                                        <td class="row">

                                            @role('developer')
                                            <a class="btn btn-success btn-sm mr-1" href="{{Url('admin/product/'.$product->id.'/approve')}}" >
                                                تایید
                                            </a>
                                            <button type="button" class="chatIdProduct btn btn-danger  btn-sm float-right mr-1"
                                                    rel="{{$product->id}}" data-toggle="modal" data-target="#modalChatbox" aria-haspopup="true"
                                                    aria-expanded="false">عدم تایید</button></span>
                                            @endrole

                                            <a class="btn btn-info btn-sm  mr-1"
                                            @role('developer')
                                            href="{{Url('admin/product/'.$product->id.'/edit')}}"
                                            @else
                                                href="{{Url('dashboard/product/'.$product->id.'/edit')}}"
                                            @endrole
                                                title="ویرایش">
                                            <i class="fas fa-pencil-alt"> </i>  </a>
                                            <form
                                                @role('developer')
                                                action="{{Url('admin/product/'.$product->id)}}"
                                                @else
                                                    action="{{Url('dashboard/product/'.$product->id)}}"
                                                @endrole
                                                    method="POST"  onsubmit="return confirm('آیا برای حذف اطمینان دارید؟');" >
                                                @csrf
                                                @method('delete')
                                                <button  type="submit" class="delete_button btn btn-danger btn-sm  mr-1 " title="حذف">
                                                    <i class="fas fa-trash"></i> </button>

                                            </form>

                                        </td>
                                        <td>{{$product->subject}}</td>
                                        <td>@if($product->availability == 1)
                                            <span class="small alert alert-warning" role="alert">
                                                در حال بررسی
                                            </span>
                                            @elseif($product->availability == 2)
                                                <span class="small alert alert-success" role="alert">
                                            منتشر شده
                                            </span>
                                            @elseif($product->availability == 3)
                                                <span class="small alert alert-secondary" role="alert">
                                            در انتظار پرداخت
                                            </span>
                                            @elseif($product->availability == 4)
                                                <span class="small alert alert-danger" role="alert">
                                                عدم تایید
                                            </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{$products->links()}}

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

    <div class="modal fade" id="modalChatbox" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title col-xs-12" id="exampleModalLongTitle">علت عدم تایید</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body  " >
                    <div class="container">
                        <form action="#" method="post" id="chat_form">
                            @csrf
                            <div class="input-group">
                                <input type="text" id="chat_box_message" name="message" placeholder="نوشتن متن ..." class="form-control">
                                <span class="input-group-append">
                                    <button type="submit" class="btn btn-primary">ارسال</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    @include('layouts.footer')
@stop
