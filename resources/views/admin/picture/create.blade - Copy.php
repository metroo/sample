@extends('admin.master')

@section('classes_body')
    hold-transition sidebar-mini layout-fixed layout-navbar-fixed
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
    <script src="{{ Url('vendor/gridjs/dist/gridjs.umd.js') }}"></script>
    <script>
        new gridjs.Grid({
            sort: true,
            fixedHeader: true,
            columns: [
                {
                    name: 'عملیات',
                    formatter: (cell, row) => {
                        return gridjs.h('button', {
                            className: 'btn btn-primary btn-block',
                            onClick: () => alert(`Editing ${row.cells[2].data}`)
                        }, gridjs.html('انتخاب'));
                    }
                },
                {
                    name: 'تصویر',
                    //data: null,
                    formatter: (_, row) =>{
                        console.log(row)
                        if(row.cells[0].data == 1)
                            return gridjs.html(`<img height='50px' src='{{Url('')}}${(row.cells[1].data )}'>`)
                    }
                },
                'شماره',
                'عنوان',
                'نام فایل',
            ],
            sort: {
                multiColumn: false,
                server: {
                    url: (prev, columns) => {
                        if (!columns.length) return prev;
                        const col = columns[0];
                        const dir = col.direction === 1 ? 'asc' : 'desc';
                        let colName = ['upload_type','filename' ,'id', 'subject', 'original_name'  ][col.index];
                        if(prev.indexOf('?') == -1)
                            return  `${prev}?order=${colName}&dir=${dir}`;
                        else
                            return `${prev}&order=${colName}&dir=${dir}`;
                    }
                }
            },
            server: {
                url: '{{Url('admin/pictureList')}}',
                then: data => data.results.map(pokemon => [
                    pokemon.upload_type , pokemon.filename , pokemon.id , pokemon.subject, pokemon.original_name
                ]),/*
                url: 'https://pokeapi.co/api/v2/pokemon',
                then: data => data.results.map(pokemon => [
                    pokemon.name, gridjs.html(`<a href='${pokemon.url}'>Link to ${pokemon.name}</a>`)
                ]),*/
                total: data => data.count
            },
            language: {
                'search': {
                    'placeholder': '🔍 جستجو ...'
                },
                'pagination': {
                    'to': 'تا',
                    'of': 'از',
                    'previous': 'قبلی',
                    'next': 'بعدی',
                    'showing': 'نمایش دادن',
                    'results': () => 'نتیجه'
                }
            },
            search: {
                enabled: true,
                server: {
                    url: (prev, keyword) => `${prev}?search=${keyword}`
                }
            },
            pagination: {
                enabled: true,
                limit: 5,
                server: {
                    url: function(prev, page, limit){
                        if(prev.indexOf('?') == -1)
                            return   `${prev}?limit=${limit}&offset=${page * limit}`;
                        else
                            return   `${prev}&limit=${limit}&offset=${page * limit}`;
                    }
                }
            },

        }).render(document.getElementById("wrapper"));
    </script>
    <script src="{{ Url('vendor/adminlte3/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ Url('vendor/adminlte3/plugins/select2/js/select2.full.js') }}"></script>
    <script src="{{ Url('vendor/adminlte3/plugins/select2/js/select2totree.js') }}"></script>
    <script src="{{ Url('vendor/vakata-jstree/dist/jstree.js') }}"></script>
    <script src="{{ Url('vendor/dropzone/dropzone.js') }}"></script>
    <script src="{{ Url('vendor/f2f/f2f.js') }}"></script>
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
                logoimg_add_id = '#category_bannerimg_add';
                logourl_add_id = '#category_bannerurl_add';
                logo_imagelogo_id = '#category_imagebanner_id';
                newDropzone.removeAllFiles(true);
                $('#uploader')[0].reset();
                $('#theModal').modal('show');
                $('#selectfilemodal').hide();
            });

            var mydata = @json($tree_categories);
            //$("#inputParent_id").val(2).trigger('change');
            $("#inputParent_id").select2ToTree({treeData: {dataArr: mydata}});


            arrFileUploadModal = [];
            modaluploadid = '';
            modaluploadurl = '';
            modaluploaded  = false;
            dropzoneOptions = {
                dictDefaultMessage: 'جهت بارگذاری ، فایل را اینجا رها کنید یا اینجا کلیک کنید',
                dictCancelUpload : 'لغو بارگذاری',
                dictCancelUploadConfirmation : 'آیا از لغو بارگذاری اطمینان دارید ؟',
                dictRemoveFile : 'حذف فایل',
                dictFileTooBig : 'حداقل اندازه فایل برای بارگذاری {{$maxFilesize}} مگ می باشد، اندازه فایل {{$filesize}} ',
                dictInvalidFileType : 'نوع فایل صحیح نمی باشد',
                dictRemoveFileConfirmation : 'آیا برای حذف اطمینان دارید',
                dictMaxFilesExceeded : ' فقط امکان بارگذاری {{$maxFiles}} فایل فراهم می باشد ',
                paramName: "file",
                maxFilesize: 2, // MB
                addRemoveLinks: true,
                init: function () {
                    this.on("success", function (file ,e) {
                        $('#selectfilemodal').show();
                        modaluploaded = true;
                        modaluploadurl = e.path;
                        modaluploadid = e.ids[0].id;
                        console.log("success > " ,  e);
                    });
                    this.on("error" , function (e) {
                        console.log('error',e);
                        modaluploaded = false;
                    });
                    this.on("addedfiles" , function (e) {
                        if(e[0].accepted){
                            arrFileUploadModal.push(e[0].lastModified);
                        }
                        console.log('addedfiles',e[0]);
                    });
                    this.on("complete", function(file) {
                        //this.removeAllFiles(true);
                    });
                },
                maxFiles : 2,
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                removedfile: function(file ) {
                    console.log('delete file' , file);
                    var fileName = file.name;

                    for( var i = 0; i < arrFileUploadModal.length; i++){
                        if ( arrFileUploadModal[i] === file.lastModified) {
                            arrFileUploadModal.splice(i, 1);
                        }
                    }

                    $.ajax({
                        type: 'POST',
                        url: '{{Url('admin/upload')}}',
                        data: {name: fileName,request: 'delete'},
                        sucess: function(data){
                            console.log('success: ' + data);
                        }
                    });

                    var _ref;
                    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                }
            };


            uploader = document.querySelector('#uploader');
            newDropzone = new Dropzone(uploader, dropzoneOptions);
        });

    </script>
@endsection

@section('adminlte_css')

    <link rel="stylesheet" href="{{ Url('vendor/gridjs/dist/theme/mermaid.rtl.css') }}" />

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
                <div id="wrapper"></div>

                        <div class="col">
                            <form action=""  method="POST" id="createForm">
                                @csrf
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
                                                    <label for="inputParent_id" class="col-sm-2 col-form-label">دسته بندی</label>
                                                    <div class="col-sm-10">
                                                        <select id="inputParent_id" name="inputParent_id"  class="form-control "  multiple>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputFull_title" class="col-sm-2 col-form-label">توضیحات</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control" id="inputFull_title" name="inputFull_title" placeholder="توضیحات"></textarea>
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
                                                        قالب صفحه
                                                    </label>
                                                    <p class="help-block margin-bottom-lg">
                                                        در صورتی که قالب خاصی برای نمایش صفحه در نظر دارید، انتخاب کنید.
                                                    </p>
                                                    <div class="page-layouts">
                                                        <a class="page-layout page-layout-active"
                                                           id="layouts-pages-store-categories"  style="">
                                                            <img class="page-layout-image" src="{{Url('images/layouts/default.svg')}}">
                                                            <div class="page-layout-title">
                                                                قالب پیشفرض
                                                            </div>
                                                        </a>
                                                        <a class="page-layout"
                                                           id="layouts-pages-gallery-catalog" >
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
                                                <div>
                                                    <span class="mailbox-attachment-icon" id="category_logoimg_add"><i class="far fa-file-image"></i></span>
                                                </div>
                                            </div>
                                            <!-- /.tab-pane -->

                                            <div class="tab-pane" id="settings">
                                                <div class="form-group row">
                                                    <label for="inputSlug" class="col-sm-2 col-form-label">پیوند ثابت</label>
                                                    <div class="col-sm-10">
                                                        <div class="input-group  " style="direction: ltr!important;">
                                                            <div class="input-group-append"  style="direction: ltr!important;">
                                                                <span class="input-group-text" style="direction: ltr!important;">http://banifatemeh.org/media/</span>
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




