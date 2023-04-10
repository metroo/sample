@extends('admin.master')

@section('classes_body')
    hold-transition sidebar-mini
@endsection

@section('navbar')
    @include('admin.navbar')
@endsection

@section('sidebar')
    @include('admin.sidebarAdmin' , ['page'=>'picture-multimedia'])
@endsection

@section('contentHeader')
    @include('admin.contentheader' , ['header' => 'ایجاد گالری تصویر' ])
@endsection

@section('adminlte_js')
    <script src="{{ Url('vendor/adminlte3/plugins/jsgrid/jsgrid.js') }}"></script>
    <script src="{{ Url('vendor/adminlte3/plugins/jsgrid/i18n/jsgrid-fa.js') }}"></script>
    <script src="{{ Url('vendor/adminlte3/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ Url('vendor/adminlte3/plugins/select2/js/select2.full.js') }}"></script>
    <script src="{{ Url('vendor/adminlte3/plugins/select2/js/select2totree.js') }}"></script>
    <script src="{{ Url('vendor/vakata-jstree/dist/jstree.js') }}"></script>
    <script src="{{ Url('vendor/dropzone/dropzone.js') }}"></script>
    <script src="{{ Url('vendor/f2f/f2f.js') }}"></script>
    <script src="{{ Url('js/modalUploader.js?ver=1') }}"></script>

    <script>
        Dropzone.autoDiscover = false;
        F2F = new f2f();
        $(document).ready(function() {

            $('#inputSubject').on('change', function () {
                $('#inputSlug').val(F2F.simplef2f($('#inputSubject').val()));
            });
            $('#inputSubject').on('change', function () {
                $('#inputSeotitle').val($('#inputSubject').val());
            });

            $('.page-layout').on('click', function (th) {
                $(".page-layouts>a.page-layout-active").removeClass("page-layout-active");
                $(this).addClass('page-layout-active');
                $('#_template').val($(this)[0].getAttribute('id'));
                //th.addClass("page-layout-active");
            });

            $('.select2bs4').select2({
                dir: "rtl" ,theme: 'bootstrap4'
            });
            $('.select2tags').select2({
                tags: true , dir: "rtl"
            });


            $('#picture_selectfile').on('click' ,function () {
                showModalUploader.initialize({
                    publicUrl : '{{Url('')}}' ,
                    formFieldId : '#imagesid' ,
                    palceImageId : '#sortable'
                });
                showModalUploader.showMulti();
            });

            var mydata = @json($tree_categories);
            //$("#inputParent_id").val(2).trigger('change');
            $("#inputParent_id").select2ToTree({treeData: {dataArr: mydata}});


        });

    </script>
@endsection

@section('adminlte_css')

    <link rel="stylesheet" href="{{ Url('vendor/adminlte3/plugins/jsgrid/jsgrid.min.css') }}" />
    <link rel="stylesheet" href="{{ Url('vendor/adminlte3/plugins/jsgrid/jsgrid-theme.min.css') }}" />

    <link rel="stylesheet" href="{{ Url('vendor/adminlte3/plugins/dropzone/min/dropzone.min.css') }}" />
    <link rel="stylesheet" href="{{ Url('vendor/adminlte3/plugins/select2/css/select2.css') }}" />
    <link rel="stylesheet" href="{{ Url('vendor/adminlte3/plugins/select2/css/select2totree.css') }}" />

    <link rel="stylesheet" href="{{ Url('vendor/adminlte3/plugins/select2-bootstrap4-theme/select2-bootstrap4.css') }}" />
    <link rel="stylesheet" href="{{ Url('vendor/vakata-jstree/dist/themes/default/style.css') }}" />
    <link rel="stylesheet" href="{{ Url('vendor/adminlte3/plugins/icheck-bootstrap/icheck-bootstrap.rtl.css') }}" />
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            color: #0c0c0d;
        }


        .page-layouts {
        }

        .page-layout {
            display: inline-block;
            width: 150px;
            padding: 15px 10px;
            background: #f7f7f7;
            cursor: pointer;
            border-radius: 8px;
            text-decoration: none !important;
            margin: 0 0 15px 10px;
        }

        .page-layout-image {
            width: 100%;
            height: auto;
            display: block
        }

        .page-layout-title {
            padding: 8px 0 0;
            text-align: center
        }

        .page-layout-active {
            background: #dfeff8
        }
    </style>
 @endsection


@section('content')
    <div class="content">
            <div class="col-md-12">
                @if(session()->get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session()->get('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if(session()->get('danger'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session()->get('danger') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                        <div class="col">
                            <form action="{{Url('admin/picture')}}"  method="POST" id="createForm">
                                @csrf
                                <input type="hidden" name="imagesid" value="" id="imagesid">
                                <input type="hidden" name="_method" value="" id="_method">
                                <input type="hidden" name="_template" value="" id="_template">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">عمومی</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#images" data-toggle="tab">تصاویر</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#template" data-toggle="tab">قالب صفحه</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">SEO</a></li>
                                        </ul>
                                    </div><!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="active tab-pane" id="activity">
                                                <div class="form-group row">
                                                    <label for="inputSubject" class="col-sm-2 col-form-label">عنوان</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="inputSubject"  name="inputSubject" placeholder="عنوان">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputFull_title" class="col-sm-2 col-form-label">توضیحات</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control" id="inputFull_title" name="inputFull_title" placeholder="توضیحات"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputParent_id" class="col-sm-2 col-form-label">دسته بندی</label>
                                                    <div class="col-sm-10">
                                                        <select id="inputParent_id" multiple="multiple" name="inputParentid[]"  class="form-control "  multiple>

                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <div class="offset-sm-2 col-sm-10">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" checked class="custom-control-input" id="inputPublished" name="inputPublished">
                                                            <label class="custom-control-label" for="inputPublished">انتشار در سایت</label>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="inputTags" class="col-sm-2 col-form-label">برچسب ها</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control select2 select2tags" multiple="multiple" id="inputTags" name="inputTags[]">
                                                            @foreach($tags as $tag)
                                                                <option value="{{$tag->id}}">{{$tag->subject}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="offset-sm-2  ">
                                                        <button type="submit" class="btn btn-primary">ذخیره</button>
                                                    </div>
                                                </div>



                                            </div>
                                            <!-- /.tab-pane -->
                                            <div class="tab-pane" id="template">

                                                <div class="form-group row">
                                                    <label for="slug" class="margin-clear">
                                                        در صورتی که قالب خاصی برای نمایش صفحه در نظر دارید، انتخاب کنید.
                                                    </label>
                                                    <div class="page-layouts">
                                                        <a class="page-layout page-layout-active"
                                                           id="layouts-pages-default"  style="">
                                                            <img class="page-layout-image" src="{{Url('images/layouts/default.svg')}}">
                                                            <div class="page-layout-title">
                                                                قالب پیشفرض
                                                            </div>
                                                        </a>
                                                        <a class="page-layout"
                                                           id="ayouts-pages-gallery-catalog" >
                                                            <img class="page-layout-image" src="{{Url('images/layouts/gallery-catalog.svg')}}">
                                                            <div class="page-layout-title">
                                                                قالب categories
                                                            </div>
                                                        </a>
                                                        <a class="page-layout "
                                                           id="layouts-pages-gallery-large"  style="">
                                                            <img class="page-layout-image" src="{{Url('images/layouts/gallery-large.svg')}}">
                                                            <div class="page-layout-title">
                                                                قالب large
                                                            </div>
                                                        </a>
                                                        <a class="page-layout"
                                                           id="layouts-pages-gallery-medium" style="">
                                                            <img class="page-layout-image" src="{{Url('images/layouts/gallery-medium.svg')}}">
                                                            <div class="page-layout-title">
                                                                قالب medium
                                                            </div>
                                                        </a>
                                                        <a class="page-layout"
                                                           id="layouts-pages-gallery-slider" >
                                                            <img class="page-layout-image" src="{{Url('images/layouts/gallery-slider.svg')}}">
                                                            <div class="page-layout-title">
                                                                قالب slider
                                                            </div>
                                                        </a>
                                                        <a class="page-layout"
                                                           id="layouts/pages/gallery/small"
                                                        >
                                                            <img class="page-layout-image" src="{{Url('images/layouts/gallery-small.svg')}}">
                                                            <div class="page-layout-title">
                                                                قالب small
                                                            </div>
                                                        </a>
                                                        <div class="clearfix"></div>
                                                    </div>

                                                </div>
                                            </div>
                                            <!-- /.tab-pane -->

                                            <div class="tab-pane" id="images">
                                                <div class="row">
                                                    <div class="col">
                                                        فهرست تصاویر را در زیر مشاهده می‌کنید. جایگاه تصاویر را می‌توانید جابجا کنید.
                                                    </div>
                                                    <a href="#" id="picture_selectfile"
                                                       class=" li-modal btn btn-default btn-sm float-right"><i class="fas fa-cloud-upload-alt"></i>
                                                        انتخاب فایل</a>
                                                 </div>
                                                <div>  <style>
                                                        #sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
                                                        #sortable li { margin: 3px 3px 3px 0; padding: 1px; float: left;
                                                              font-size: 4em; text-align: center; }

                                                    </style>

                                                    <ul id="sortable">

                                                    </ul>
                                                </div>
                                            </div>
                                            <!-- /.tab-pane -->

                                            <div class="tab-pane" id="settings">
                                                <div class="form-group row">
                                                    <label for="inputSlug" class="col-sm-2 col-form-label">پیوند ثابت</label>
                                                    <div class="col-sm-10">
                                                        <div class="input-group  " style="direction: ltr!important;">
                                                            <div class="input-group-append"  style="direction: ltr!important;">
                                                                <span class="input-group-text" style="direction: ltr!important;">http://banifatemeh.org/picture/</span>
                                                            </div>
                                                            <input id="inputSlug" name="inputSlug" style="direction: ltr!important;"
                                                                   type="text" class="form-control"  >
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputSeotitle" class="col-sm-2 col-form-label">تیتر صفحه</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="inputSeotitle" name="inputSeotitle" placeholder="SEO Title">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputSeodescription" class="col-sm-2 col-form-label">شرح مختصر</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control" id="inputSeodescription" name="inputSeodescription" placeholder="SEO description"></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- /.tab-pane -->
                                        </div>
                                        <!-- /.tab-content -->
                                    </div><!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </form>
                        </div>



        </div>
    </div>

@endsection

@section('footer')
    @include('admin.footer')
@endsection

@section('adminlte_js')
     <script>
        $(function () {

        });
    </script>
@endsection




