@extends('admin.master')

@section('classes_body')
    hold-transition sidebar-mini
@endsection

@section('navbar')
    @include('admin.navbar')
@endsection

@section('sidebar')
    @include('layouts.sidebar' , ['page'=>'productIndex'])
@endsection

@section('contentHeader')
    @include('admin.contentheader' , ['header' => '' ])
@endsection

@section('adminlte_css')
    <link rel="stylesheet" href="{{ Url('css/bsdrilldown.css') }}">
    <link rel="stylesheet" href="{{ Url('vendor/dropzone/dropzone.css') }}">
    <link rel="stylesheet" href="{{ Url('vendor/adminlte/plugins/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ Url('vendor/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ Url('vendor/leaflet/leaflet.css') }}">
    <style>
        .swal2-popup.swal2-toast .swal2-title {
            margin: 0px 10px 0px 10px;
            color: #6c757d;
        }
        #mapid { height: 300px; }
    </style>
@endsection


@section('content')
    <div class="content">
        <div class="col-md-12">
            <div class="card card-outline card-primary" id="divHerirachy">
                <div class="card-header">
                    <i class="fas fa-chart-pie mr-1"></i>
                    ویرایش آگهی
                </div>
                <div class="card-body"  >

                    <div id="categoryInfo" style="display:none" class="mb-2">

                        <a href="#"  id="changeCategory" class="col-3  btn btn-outline-secondary">تغییر گروه آگهی</a>
                    </div>
                    <div id="categoryContainer">
                        لطفا گروه آگهی خود را انتخاب نمایید
                        <script>
                            myData = {
                                "ID":-1,
                                "Name":"لیست گروه ها",
                                "ChildData":[
                                        @foreach($categories as $category)
                                    {
                                        "ID": {{$category->id}},
                                        "Name": "{{$category->title}}",
                                        @if($category->sub_category->count())
                                        "ChildData": [
                                            @include('admin.category.subCategoryList',['subcategories' => $category->sub_category])
                                        ]
                                        @endif
                                    },
                                    @endforeach
                                ]
                            };
                        </script>
                        <div class="callout " id="hp_header">

                        </div>
                        <ul id="hp_body" class="hierarchy-custom">

                        </ul>
                        <button id="btnCatDone" class="btn btn-block btn-primary">انتخاب</button>
                    </div>
                    <div id="productCreate"></div>

                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <br />
                @endif

                <div  class="overlay productCreateOverly" style="display: none;" >
                    <i class="spinner"></i>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('footer')
    @include('layouts.footer')
@endsection

@section('adminlte_js')
    <script src="{{ Url('js/bsdrilldown.js') }}"></script>
    <script src="{{ Url('vendor/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ Url('vendor/leaflet/leaflet.js') }}"></script>

    <script>
        var categoryShow = true;

        var pImages = {!! $images !!};
        var imagesUploads = [];
        var public_path = "{!! url('/')  !!}";var currentNodes = [{{$sc_data->category}}];
        var cities = {};var mymap=null;var marker=null;var FormDatas = {!!$product->sc_data!!};
        var formUrl = mainUrl+'/dashboard/product/{!!$product->id!!}';
        var formMethod = 'PUT';
        $(function () {
            $('.select2').select2();

            BsDrillDown.initialize({
                headerEleClass: 'text-danger',
                headerSelector: '#hp_header',
                hpBodySelector: '#hp_body',
                keyField: 'ID',
                displayField: 'Name',
                ChildField: 'ChildData',
                //levelField: 'Level',
                defaultState:[-1],
                empLevel:5
            }, myData);


            $('#hp_body').bind("DOMSubtreeModified",function(){
                if(!Object.keys(BsDrillDown.getCurrentNodeFull().Child).length){
                    $('#btnCatDone').trigger('click');
                }
            });

            function getcity(){
                $.get(mainUrl+"/dashboard/city", function( data ) {
                    //me.buyButtonReact(data , { "category" : ProductCategories});
                }).done(function(data) {
                    //alert( "second success" );
                    cities = data;
                    BsDrillDown.SelectHerirachyByKeys([-1,{{$sc_data->category}}]);
                    $('#btnCatDone').trigger('click');
                })
                .fail(function() {
                    alert( "خطا در دریافت داده ، تلاش مجدد !" );
                    getcity();
                });
            }

            getcity();

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            Toast = Swal.mixin({
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            $('#changeCategory').on('click', function () {
                categoryShow = true;
                BsDrillDown.showHP();
                $("#categoryContainer").show();
                $("#categoryInfo").hide();
                $("#productCreate2").hide();
            });
            /* $('#btnCatDone').on('click', function () {
                 BsDrillDown.doneSelection(function (currentNode, confirmFn) {
                     if(currentNode.ID != -1) {
                         $.get( "{{ Url('/dashboard/schema') }}/"+currentNode.ID, function( data ) {
                            buyButtonReact(data,null);
                        });
                        var selection = confirmFn();
                        ProductCategories = selection.slice(0);
                        ProductCategories.shift();
                        console.log(selection);
                        console.log(currentNode.ID);
                        ProductSlug = currentNode.ID;
                        $("#categoryContainer").hide();
                        $("#categoryInfo").show();
                        $("#categoryInfoTitle").html(currentNode.Name);
                    }
                 });
            });*/


            /*BsDrillDown.doneSelection(function (currentNode, confirmFn) {
                if (currentNode.ID != -1) {
                    var selection = confirmFn();
                    ProductCategories = selection.slice(0);
                    ProductCategories.shift();
                    $.get( mainUrl+"/dashboard/schema/"+currentNode.ID, function( data ) {
                        me.buyButtonReact(data , {location: {city: 188 } , category: ProductCategories.toString()});
                    });
                    ProductSlug = currentNode.ID;
                    $("#categoryContainer").hide();
                    $("#categoryInfo").show();
                    $("#categoryInfoTitle").html(currentNode.Name);
                }
            });*/
            BsDrillDown.showHP();

        });
    </script>
@endsection




