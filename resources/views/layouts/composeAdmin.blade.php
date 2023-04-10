@extends('layouts.master')

@section('classes_body')
    hold-transition sidebar-mini
@endsection

@section('navbar')
    @include('admin.navbar')
@endsection

@section('sidebar')
    @include('layouts.sidebar', ['page'=>'composeAdmin'])
@endsection

@section('contentHeader')
    @include('admin.contentheader' , ['header' => '' ])
@endsection


@section('content')
    <div class="content">

        <div class="col-md-12">
            <form action="{{ Url('/dashboard/mailbox') }}" method="post">
                @csrf
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">ارسال پیام به مدیر سایت</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <div class="form-group">
                            <input class="form-control" name="title" placeholder="عنوان :">
                        </div>
                        <div class="form-group">
                    <textarea id="compose-textarea" name="message" class="form-control" style="height: 300px">

                    </textarea>
                        </div>
                        <div class="form-group">
                            <div class="btn btn-default btn-file disabled">
                                <i class="fas fa-paperclip"></i> پیوست فایل
                                <input type="file" name="attachment" disabled>
                            </div>
                            <p class="help-block">Max. 32MB</p>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="float-right">
                             <button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> ارسال</button>
                        </div>
                        <button type="reset" class="btn btn-default"><i class="fas fa-times"></i> پاک کردن</button>
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
            </form>
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
