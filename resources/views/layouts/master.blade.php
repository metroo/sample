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
                                                        <form action="{{Url('admin/upload')}}" class="dropzone" id="uploader"
                                                              enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="form-group row">
                                                                <label for="inputTitle" class="col-sm-2 col-form-label">عنوان</label>

                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" id="inputTitle"  name="inputTitle" placeholder="عنوان">
                                                                </div>
                                                            </div> <div class="alert alert-danger" role="alert">
                                                                جهت انتصاب عنوان به فایل ها ، قبل از انتخاب فایل ، کادر عنوان را تکمیل یا تغییر دهید
                                                            </div>
                                                            <div class="dz-message" data-dz-message><span>جهت بارگذاری فایل را اینجا رها کنید یا اینجا کلیک کنید</span></div>


                                                        </form> <a href="#" id="selectfilemodal" class="btn btn-success">انتخاب فایل بارگذاری شده</a>
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
                                <h3 class="card-title">Simple Full Width Table</h3>

                                <div class="card-tools">
                                    <div class="input-group input-group-sm float-right" style="width: 150px;">
                                        <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <ul class="pagination pagination-sm float-right">
                                        <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Task</th>
                                        <th>Progress</th>
                                        <th style="width: 40px">Label</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>Update software</td>
                                        <td>
                                            <div class="progress progress-xs">
                                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-danger">55%</span></td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Clean database</td>
                                        <td>
                                            <div class="progress progress-xs">
                                                <div class="progress-bar bg-warning" style="width: 70%"></div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-warning">70%</span></td>
                                    </tr>
                                    <tr>
                                        <td>3.</td>
                                        <td>Cron job running</td>
                                        <td>
                                            <div class="progress progress-xs progress-striped active">
                                                <div class="progress-bar bg-primary" style="width: 30%"></div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-primary">30%</span></td>
                                    </tr>
                                    <tr>
                                        <td>4.</td>
                                        <td>Fix and squish bugs</td>
                                        <td>
                                            <div class="progress progress-xs progress-striped active">
                                                <div class="progress-bar bg-success" style="width: 90%"></div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">90%</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-two-messages" role="tabpanel" aria-labelledby="custom-tabs-two-messages-tab">
                            Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus volutpat augue id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec hendrerit sem, ac tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut velit condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit finibus tristique. Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit amet sollicitudin est libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus ipsum gravida arcu, id fermentum metus arcu vel metus. Curabitur eget sem eu risus tincidunt eleifend ac ornare magna.
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-two-settings" role="tabpanel" aria-labelledby="custom-tabs-two-settings-tab">
                            Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate. Morbi euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec interdum placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam. Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet accumsan ex sit amet facilisis.
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
