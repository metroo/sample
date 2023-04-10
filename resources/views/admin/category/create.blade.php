@extends('layouts.master')

@section('classes_body')
    hold-transition sidebar-mini layout-fixed layout-navbar-fixed
@endsection

@section('navbar')
    @include('admin.navbar')
@endsection

@section('sidebar')
    @include('layouts.sidebar' , ['page'=>'productCreate'])
@endsection

@section('contentHeader')
    @include('admin.contentheader' , ['header' => '' ])
@endsection

@section('adminlte_css')
    <link rel="stylesheet" href="{{ Url('css/bsdrilldown.css') }}">
@endsection


@section('content')
    <div class="content">
            <div class="col-md-12">
                <div class="card card-outline card-primary" id="divHerirachy">
                    <div class="card-header">
                        <i class="fas fa-chart-pie mr-1"></i>
                        ثبت رایگان آگهی
                    </div>
                    <div class="card-body"  >

                        <div id="categoryInfo" style="display:none">
                            <div class="callout ">
                                <h5 id="categoryInfoTitle" > </h5>
                                <span id="changeCategory" class="text-primary">تغییر دسته بندی</span>
                            </div>
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
    <script>
        $(function () {
            $('#changeCategory').on('click', function () {
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
            BsDrillDown.initialize({
                headerEleClass: 'text-danger',
                headerSelector: '#hp_header',
                hpBodySelector: '#hp_body',
                keyField: 'ID',
                displayField: 'Name',
                ChildField: 'ChildData',
                //levelField: 'Level',
                defaultState:[-1],
                empLevel:3
            }, myData);
            BsDrillDown.showHP();

        });
    </script>
@endsection




