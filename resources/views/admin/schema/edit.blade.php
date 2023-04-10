@extends('layouts.master')

@section('adminlte_css')
    <style type="text/css">

        #json-input {
            display: block;
            width: 100%;
            height: 200px;
        }
      </style>
@stop

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
    @include('admin.contentheader' , ['header' => 'ویرایش فرم گروه ' ])
@stop


@section('content')
    <div class="content">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{$schemaJson->title}}</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" action="../{{$schemaJson->cat_id}}" method="POST">
                    {{csrf_field()}}
                    {{method_field('put')}}
                    <div class="card-body">
                        <div class="form-group">
                            <label>Schema Json</label>
                            <textarea id="json-input" name="sc_json" class="form-control" rows="2" placeholder="Enter ...">{{$schemaJson->sc_json}}</textarea>
                        </div>
                        <iframe id="jsonframe" src="{{ Url('vendor/json-viewer/index.html') }}"
                                height="800px" class="col-12" ></iframe>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary col-12">ثبت</button>
                    </div>
                </form>
                <div class="card-footer">
                    <div class="form-group">
                        <button id="sendJson" class="btn btn-primary col-sm-3" >بارگزاری جهت ویرایش</button>
                        <button id="loadJson" class="btn btn-primary  col-sm-3">اعمال تغییرات در نسخه اصلی</button>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@stop

@section('footer')
    @include('layouts.footer')
@stop

@section('adminlte_js')
     <script type="text/javascript">
        $(function () {
            $('#loadJson').on('click', function () {
                var check = $('#jsonframe');
                var win=check[0].contentWindow
                win.getJson();
                console.log('loadjs',win.sc_json);
                $('#json-input').val(JSON.stringify(win.sc_json));
            });

            $('#sendJson').on('click', function () {
                var check = $('#jsonframe');
                var win = check[0].contentWindow;
                win.sc_json = $('#json-input').val();
                win.setJson();
            });
        });
    </script>
@stop

