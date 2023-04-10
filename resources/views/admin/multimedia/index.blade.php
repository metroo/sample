@extends('admin.master')

@section('classes_body')
    hold-transition sidebar-mini
@stop
<?PHP
/*
 * https://github.com/clivezhg/select2-to-tree
 *
https://github.com/brothersincode/f2f
<script src="lib/f2f.js"></script>
<script>
    var F2F = new f2f();
    alert(F2F.simplef2f("قسطنطنیه"));
</script>
*/
    ?>
@section('adminlte_js')
    <script src="{{ Url('vendor/adminlte3/plugins/jquery-ui/jquery-ui.min.js') }}"></script>

    <script src="{{ Url('vendor/adminlte3/plugins/select2/js/select2.full.js') }}"></script>
    <script src="{{ Url('vendor/adminlte3/plugins/select2/js/select2totree.js') }}"></script>
    <script src="{{ Url('vendor/vakata-jstree/dist/jstree.js') }}"></script>
    <script src="{{ Url('vendor/dropzone/dropzone.js') }}"></script>
    <script src="{{ Url('vendor/f2f/f2f.js') }}"></script>
    <script src="{{ Url('vendor/adminlte3/plugins/jsgrid/jsgrid.js') }}"></script>
    <script src="{{ Url('vendor/adminlte3/plugins/jsgrid/i18n/jsgrid-fa.js') }}"></script>
    <script src="{{ Url('js/modalUploader.js?ver=1') }}"></script>
    <script>
        F2F = new f2f();
        createFormUrl = "{{Url('admin/multimedia/categories')}}";
        Dropzone.autoDiscover = false;
        $(document).ready(function() {
            $('.copyp').on('click', function() {
                var r = $(this).parent().parent().find(':input');
                var e = r.select();
                document.execCommand("copy");
            });
            $('#createForm').attr('action' , createFormUrl);
            open = true;
            $.jstree.defaults.dnd.check_while_dragging = false;
            $.jstree.defaults.contextmenu.items = function (o, cb) { // Could be an object directly
                return {
                    /*"create" : {
                        "separator_before"	: false,
                        "separator_after"	: true,
                        "_disabled"			: false, //(this.check("create_node", data.reference, {}, "last")),
                        "label"				: "زیر منو جدید",
                        "action"			: function (data) {

                            var inst = $.jstree.reference(data.reference),
                                obj = inst.get_node(data.reference);
                            console.log("create node",data,inst,obj)
                            inst.create_node(obj, {}, "last", function (new_node) {
                                try {
                                    console.log("new node",new_node)
                                    inst.edit(new_node);
                                } catch (ex) {
                                    setTimeout(function () { inst.edit(new_node); },0);
                                }
                            });
                        }
                    },*/
                    "rename" : {
                        "separator_before"	: false,
                        "separator_after"	: false,
                        "_disabled"			: false, //(this.check("rename_node", data.reference, this.get_parent(data.reference), "")),
                        "label"				: "تغییر نام",
                        /*!
                        "shortcut"			: 113,
                        "shortcut_label"	: 'F2',
                        "icon"				: "glyphicon glyphicon-leaf",
                        */
                        "action"			: function (data) {
                            var inst = $.jstree.reference(data.reference),
                                obj = inst.get_node(data.reference);
                            inst.edit(obj);
                        }
                    },
                    "remove" : {
                        "separator_before"	: false,
                        "icon"				: false,
                        "separator_after"	: false,
                        "_disabled"			: false, //(this.check("delete_node", data.reference, this.get_parent(data.reference), "")),
                        "label"				: "حذف",
                        "action"			: function (data) {
                            var inst = $.jstree.reference(data.reference),
                                obj = inst.get_node(data.reference);
                            if(inst.is_selected(obj)) {
                                Swal.fire({
                                    title: 'آیا برای حذف اطمینان دارید؟',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'حذف',
                                    cancelButtonText: 'لغو',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        inst.delete_node(inst.get_selected());
                                    }
                                })

                            }
                            else {
                                inst.delete_node(obj);
                            }
                        }
                    },
                    "ccp" : {
                        "separator_before"	: true,
                        "icon"				: false,
                        "separator_after"	: false,
                        "label"				: "بیشتر",
                        "action"			: false,
                        "submenu" : {
                            "cut" : {
                                "separator_before"	: false,
                                "separator_after"	: false,
                                "label"				: "برش",
                                "action"			: function (data) {
                                    var inst = $.jstree.reference(data.reference),
                                        obj = inst.get_node(data.reference);
                                    if(inst.is_selected(obj)) {
                                        inst.cut(inst.get_top_selected());
                                    }
                                    else {
                                        inst.cut(obj);
                                    }
                                }
                            },
                            /*"copy" : {
                                "separator_before"	: false,
                                "icon"				: false,
                                "separator_after"	: false,
                                "label"				: "کپی",
                                "action"			: function (data) {
                                    var inst = $.jstree.reference(data.reference),
                                        obj = inst.get_node(data.reference);
                                    if(inst.is_selected(obj)) {
                                        inst.copy(inst.get_top_selected());
                                    }
                                    else {
                                        inst.copy(obj);
                                    }
                                }
                            },*/
                            "paste" : {
                                "separator_before"	: false,
                                "icon"				: false,
                                "_disabled"			: function (data) {
                                    return !$.jstree.reference(data.reference).can_paste();
                                },
                                "separator_after"	: false,
                                "label"				: "گذاشتن",
                                "action"			: function (data) {
                                    var inst = $.jstree.reference(data.reference),
                                        obj = inst.get_node(data.reference);
                                    inst.paste(obj);
                                }
                            }
                        }
                    }
                };
            }

            $('#category').jstree({
                "plugins" : [ "contextmenu" , "dnd" ],
                'core': {
                    'force_text' : true,
                    "check_callback" : true,
                    'check_callback' : function (operation, node, node_parent, node_position, more) {
                        // operation can be 'create_node', 'rename_node', 'delete_node', 'move_node', 'copy_node' or 'edit'
                        // in case of 'rename_node' node_position is filled with the new node name
                        //return operation === 'move_node' ? true : false;
                        switch (operation){
                            case 'delete_node' :
                                console.log(node);
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    url: "{{ url('admin/multimedia/categories') }}/" + parseInt(node.id.replace('tree','')) ,
                                    data : {'func':'deletenode' ,'id' : parseInt(node.id.replace('tree','')) },
                                    method: "get",
                                    success: function (data) {
                                        if(data.success)
                                            Swal.fire('با موفقیت حذف گردید', '', 'success')
                                        else
                                            Swal.fire('مشکلی در حذف داده رخ داده است', 'برای نمایش مجدد منو صفحه را مجدد بارگذاری کنید', 'error')
                                    }
                                });
                                break;
                            case 'move_node' :
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    url: "{{ url('admin/multimedia/categories') }}/" + parseInt(node.id.replace('tree','')) ,
                                    data : {'func':'updateparent' ,'id' : parseInt(node.id.replace('tree','')), 'parentid':parseInt(node_parent.id.replace('tree',''))},
                                    method: "get",
                                    success: function (data) {

                                    }
                                });
                                break;
                            case "rename_node" :
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    url: "{{ url('admin/multimedia/categories') }}/" + parseInt(node.id.replace('tree','')) ,
                                    data : {'func':'renamenode' ,'id' : parseInt(node.id.replace('tree','')), 'newname':node_position},
                                    method: "get",
                                    success: function (data) {

                                    }
                                });
                                break;
                        }
                    },
                    'data': @json($category)
                }
            });
            $('#html1').jstree();
            $('#inputSubject').on('change', function () {
                 $('#inputSlug').val(F2F.simplef2f($('#inputSubject').val()));
            });
            $('#inputSubject').on('change', function () {
                 $('#inputSeotitle').val($('#inputSubject').val());
            });

            ref = $('#category').jstree(true);
            $('#opencat').on('click', function () {

                 if(open){
                     open = false;
                     $('#opencat').html('بازکردن همه');
                     ref.close_all()
                 }else {
                     $('#opencat').html('بستن همه');
                     ref.open_all();
                     open = true;
                 }
            });

            $('.page-layout').on('click', function (th) {
                $(".page-layouts>a.page-layout-active").removeClass("page-layout-active");
                $(this).addClass('page-layout-active');
                $('#_template').val($(this)[0].getAttribute('id'));
                //th.addClass("page-layout-active");
            });

            $('#category')
                // listen for event
                .on('changed.jstree', function (e, data) {
                    console.log('tree click');
                    var i, j, r = [];
                    for(i = 0, j = data.selected.length; i < j; i++) {
                        r.push(data.instance.get_node(data.selected[i]).text);
                    }
                    if(data.instance.get_node(data.selected[0]).id)
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url:"{{ url('admin/multimedia/categories') }}/"+data.instance.get_node(data.selected[0]).id+"/edit",
                        method:"get",
                        success:function(data){
                            console.log(data)
                            if(data.hasOwnProperty("success")) {
                                if (data.success) {
                                    $('#inputSubject').val(data.cat.title)
                                    $('#inputSlug').val(data.cat.slug)
                                    $('#inputFull_title').val(data.cat.full_title)
                                    $('#inputOrdering').val(data.cat.ordering)
                                    $('#inputSeotitle').val(data.cat.seo_title)
                                    $('#inputSeodescription').val(data.cat.seo_description)
                                    $('#inputPublished').prop("checked", (data.cat.published != null) ? data.cat.published : 1);
                                    $('#inputCategory_type').prop("checked", (data.cat.category_type == 3) ? 1 : 0);
                                    $("#inputParent_id").val(data.cat.parent_id).trigger('change');
                                    $("#categoryImage_formFieldId").val(data.cat.logo);
                                    $("#categoryBanner_formFieldId").val(data.cat.banner);
                                    if (data.logo) {
                                        $('#categoryImage_palceImageId').html('<img src="{{Url('')}}' + data.logo.filename + '" height="120px">')
                                        $('#categoryImage_formFieldUrl').val(data.logo.filename);
                                    } else {
                                        $('#categoryImage_palceImageId').html('<i class="far fa-file-image"></i>')
                                        $('#categoryImage_formFieldUrl').val('');
                                    }
                                    if (data.banner) {
                                        $('#categoryBanner_palceImageId').html('<img src="{{Url('')}}' + data.banner.filename + '" height="120px">')
                                        $('#categoryBanner_formFieldUrl').val(data.banner.filename);
                                    } else {
                                        $('#categoryBanner_palceImageId').html('<i class="far fa-file-image"></i>')
                                        $('#categoryBanner_formFieldUrl').val('');
                                    }
                                    if (data.cat.template) {
                                        $(".page-layouts>a.page-layout-active").removeClass("page-layout-active");
                                        $("#" + data.cat.template).addClass('page-layout-active');
                                        $('#_template').val(data.cat.template);
                                    }else{
                                        $(".page-layouts>a.page-layout-active").removeClass("page-layout-active");
                                        $('#_template').val("");
                                    }
                                    $('#_method').val('PUT');
                                    $('#createForm').attr('action' , createFormUrl+'/'+data.cat.id);
                                    $('#inputTags').val(data.tags);
                                    $('#inputTags').trigger('change');

                                } else {
                                    alert('error')
                                }
                            }
                        }
                    });
                })
                .jstree();
            $('.select2bs4').select2({
                dir: "rtl" ,theme: 'bootstrap4'
            });
            $('.select2tags').select2({
                tags: true , dir: "rtl"
            });

            var mydata = @json($tree_categories);
            //$("#inputParent_id").val(2).trigger('change');
            $("#inputParent_id").select2ToTree({treeData: {dataArr: mydata}});


           /* $('.li-modal').on('click', function(e){
                e.preventDefault();
                $('#theModal').modal('show').find('.modal-body').load($('.li-modal').attr('href'));*/

            $('#categoryImage_selectfileBtn').on('click' ,function () {
                showModalUploader.initialize({
                    publicUrl : '{{Url('')}}' ,
                    formFieldId : '#categoryImage_formFieldId' ,
                    palceImageId : '#categoryImage_palceImageId',
                    formFieldUrl : '#categoryImage_formFieldUrl'
                });
                showModalUploader.show();
            });
            $('#categoryBanner_selectfileBtn').on('click' ,function () {
                showModalUploader.initialize({
                    publicUrl : '{{Url('')}}' ,
                    formFieldId : '#categoryBanner_formFieldId' ,
                    palceImageId : '#categoryBanner_palceImageId',
                    formFieldUrl : '#categoryBanner_formFieldUrl'
                });
                showModalUploader.show();
            });


        });


    </script>
@stop
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
@stop

@section('navbar')
    @include('admin.navbar')
@stop

@section('sidebar')
    @include('admin.sidebarAdmin' , ['page'=>'category-multimedia'])
@stop


@section('contentHeader')
    @include('admin.contentheader' , ['header' => 'لیست گروه چند رسانه' ])
@stop


@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
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


            <div class="row">
                <div class="col-md-auto">

                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <a href="#" class="btn btn-primary " id="opencat">بستن همه</a>
                            <a href="{{Url('admin/multimedia/categories')}}" class="btn btn-primary end">جدید</a>
                            <div class="alert alert-warning alert-dismissible fade show mt-1" role="alert">
                                <small>
                                برای تنظیمات بیشتر روی عنواین راست کلیک کنید
                                </small>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div id="category" class="demo mt-1">
                            </div>


                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                </div>
                <!-- /.col -->
                <div class="col">
                    <form action=""  method="POST" id="createForm">
                        @csrf
                        <input type="hidden" name="_method" value="" id="_method">
                        <input type="hidden" name="_template" value="" id="_template">
                        <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">عمومی</a></li>
                                <li class="nav-item"><a class="nav-link" href="#images" data-toggle="tab">تصویر</a></li>
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
                                            <label for="inputParent_id" class="col-sm-2 col-form-label">منوی والد</label>
                                            <div class="col-sm-10">
                                                <select id="inputParent_id" name="inputParent_id"  class="form-control " >

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
                                            <label for="inputOrdering" class="col-sm-2 col-form-label">ترتیب</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="inputOrdering" name="inputOrdering" placeholder="ترتیب">
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
                                            <div class="offset-sm-2 col-sm-10">
                                                <div class="custom-control custom-switch">

                                                    <input type="checkbox"  class="custom-control-input" id="inputCategory_type" name="inputCategory_type">
                                                    <label class="custom-control-label" for="inputCategory_type"> چند رسانه

                                                        <span class="small">&nbsp;
                                                        جهت فعال سازی منوی انتخاب چند رسانه در سایت
                                                    </span>

                                                    </label>
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
                                               id="layouts-pages-store-categories"  style="">
                                                <img class="page-layout-image" src="{{Url('images/layouts/default.svg')}}">
                                                <div class="page-layout-title">
                                                    قالب پیشفرض
                                                </div>
                                            </a>
                                            <a class="page-layout"
                                               id="layouts-pages-store-categories" >
                                                <img class="page-layout-image" src="{{Url('images/layouts/store-categories.svg')}}">
                                                <div class="page-layout-title">
                                                    قالب categories
                                                </div>
                                            </a>
                                            <a class="page-layout "
                                               id="layouts-pages-store-compact"  style="">
                                                <img class="page-layout-image" src="{{Url('images/layouts/store-compact.svg')}}">
                                                <div class="page-layout-title">
                                                    قالب compact
                                                </div>
                                            </a>
                                            <a class="page-layout"
                                               id="layouts-pages-store-full" style="">
                                                <img class="page-layout-image" src="{{Url('images/layouts/store-full.svg')}}">
                                                <div class="page-layout-title">
                                                    قالب full
                                                </div>
                                            </a>
                                            <a class="page-layout"
                                               id="layouts-pages-store-list" >
                                                <img class="page-layout-image" src="{{Url('images/layouts/store-list.svg')}}">
                                                <div class="page-layout-title">
                                                    قالب list
                                                </div>
                                            </a>
                                            <a class="page-layout"
                                               id="layouts/pages/store/simple"
                                            >
                                                <img class="page-layout-image" src="{{Url('images/layouts/store-simple.svg')}}">
                                                <div class="page-layout-title">
                                                    قالب simple
                                                </div>
                                            </a>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="images">
                                    <div class="row">
                                        <div class=" col-sm-12 col-md-6">
                                            <div class="card ">
                                                <div class="card-header">
                                                    <div class="row">
                                                        <h3 class="card-title col">

                                                            تصویر لوگو
                                                        </h3>
                                                        <input type="text" id="categoryImage_formFieldId" name="categoryImage_formFieldId"
                                                               class="form-control col-4" placeholder="کد تصویر" >
                                                    </div>
                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body row">
                                                    <div class="col-sm-12 ">

                                                        <div class="col-sm-12 ">
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <button type="button" class="copyp btn btn-danger"><i class="fas fa-copy"></i></button>
                                                                </div>
                                                                <!-- /btn-group -->
                                                                <input style="direction: ltr!important;"  type="text"
                                                                       id="categoryImage_formFieldUrl" name="categoryImage_formFieldUrl"
                                                                       type="text" class="form-control" placeholder="آدرس فایل">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 ">
                                                                    <span class="mailbox-attachment-icon" id="categoryImage_palceImageId">
                                                                        <i class="far fa-file-image"></i></span>
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->
                                                <div class="card-footer">
                                                                 <span   id="categoryImage_selectfileBtn"
                                                                         class=" li-modal btn btn-info btn-sm float-right">
                                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                                انتخاب فایل</span>
                                                </div>
                                            </div>
                                            <!-- /.card -->
                                        </div>
                                        <div class=" col-sm-12 col-md-6">
                                            <div class="card ">
                                                <div class="card-header">
                                                    <div class="row">
                                                        <h3 class="card-title col">

                                                            تصویر بنر
                                                        </h3>
                                                        <input type="text" id="categoryBanner_formFieldId" name="categoryBanner_formFieldId"
                                                               class="form-control col-4" placeholder="کد تصویر" >
                                                    </div>
                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body row">
                                                    <div class="col-sm-12 ">

                                                        <div class="col-sm-12 ">
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <button type="button" class="copyp btn btn-danger"><i class="fas fa-copy"></i></button>
                                                                </div>
                                                                <!-- /btn-group -->
                                                                <input style="direction: ltr!important;"  type="text"
                                                                       id="categoryBanner_formFieldUrl" name="categoryImage_formFieldUrl"
                                                                       type="text" class="form-control" placeholder="آدرس فایل">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 ">
                                                                    <span class="mailbox-attachment-icon" id="categoryBanner_palceImageId">
                                                                        <i class="far fa-file-image"></i></span>
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->
                                                <div class="card-footer">
                                                                 <span   id="categoryBanner_selectfileBtn"
                                                                         class=" li-modal btn btn-info btn-sm float-right">
                                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                                انتخاب فایل</span>
                                                </div>
                                            </div>
                                            <!-- /.card -->
                                        </div>

                                    </div>


                                    <!-- The timeline -->


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

                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@stop

@section('footer')
    @include('layouts.footer')
@stop
