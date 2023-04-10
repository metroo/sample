<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', config('APP_NAME', 'Admin Panel'))</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ Url('vendor/adminlte3/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
     <!-- pace-progress -->
    <link rel="stylesheet" href="{{ Url('vendor/adminlte3/plugins/pace-progress/themes/black/pace-theme-flat-top.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ Url('vendor/adminlte3/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.css') }}">
    <link rel="stylesheet" href="{{ Url('vendor/adminlte3/dist/css/adminlte.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ Url('vendor/adminlte3/dist/css/custom.css') }}">

    @yield('adminlte_css')
    <!-- Google Font: Source Sans Pro -->

    @yield('meta_tags')
    <script>
        var mainUrl = "{{Url("")}}";
        var ProductCategories = "";
        var ProductImages = new Array();
        var ProductSlug = "";
        var ProductScJson = "";
    </script>
</head>
<body class="@yield('classes_body') pace-primary" @yield('body_data') >
<div class="modal fade text-center" id="theModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="card-primary card-tabs  border-light ">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                        <li class="pt-2 px-3"><h3 class="card-title">انتخاب فایل</h3></li>
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill"
                               href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">بارگذاری</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill"
                               href="#custom-tabs-two-profile" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">لیست فایل های سرور داخلی</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-two-messages-tab" data-toggle="pill"
                               href="#custom-tabs-two-messages" role="tab" aria-controls="custom-tabs-two-messages" aria-selected="false">لیست فایل های سرور بیرونی</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-two-settings-tab" data-toggle="pill"
                               href="#custom-tabs-two-settings" role="tab" aria-controls="custom-tabs-two-settings" aria-selected="false">لینک خارجی</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body bg-transparent">
                    <div class="tab-content" id="custom-tabs-two-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-two-home" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">

                            <div class="content-wrap">
                                <div class="row">
                                    <div class="col-sm-12">

                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div id="dropzone">

                                                         <a href="#" id="selectfilemodal" class="btn btn-success">انتخاب فایل بارگذاری شده</a>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
                            <div class="card-header">


                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <div id="gridwrapper"></div>
                                <div id="externalPager"></div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-two-messages" role="tabpanel" aria-labelledby="custom-tabs-two-messages-tab">
                            بزودی
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-two-settings" role="tabpanel" aria-labelledby="custom-tabs-two-settings-tab">
                            بزودی
                        </div>
                    </div>
                </div>
                <!-- /.card -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>
<div class="wrapper">
    <!-- Navbar -->
    @yield('navbar')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @yield('sidebar')
    <!-- /.Sidebar Container -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        @yield('contentHeader')
        <!-- Main content -->
        @yield('content')
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- footer -->
    @yield('footer')

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


<!-- App -->
<!-- pace-progress -->
<script src="{{ Url('vendor/adminlte3/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ Url('vendor/adminlte3/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ Url('vendor/adminlte3/plugins/pace-progress/pace.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ Url('vendor/adminlte3/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ Url('vendor/adminlte3/dist/js/adminlte.min.js') }}"></script>




@yield('adminlte_js')

</body>
</html>
