<!DOCTYPE html>
<html>
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
<body class="@yield('classes_body') pace-primary " @yield('body_data') >
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
<script src="{{ Url('js/app.js') }}"></script>
<!-- pace-progress -->
<script src="{{ Url('vendor/adminlte3/plugins/pace-progress/pace.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ Url('vendor/adminlte3/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ Url('vendor/adminlte3/dist/js/adminlte.min.js') }}"></script>



@yield('adminlte_js')

</body>
</html>
