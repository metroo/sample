<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ Url('vendor/adminlte/dist/img/AdminLTELogo.png') }}"
             alt="Metroo Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name', 'وب نت') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ Url('vendor/adminlte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item has-treeview  @isset($page) @if($page =='categoryIndex' or $page =='productIndex') menu-open @endif @endisset ">

                    <a href="#" class="nav-link  @isset($page) @if($page =='categoryIndex' or $page =='productIndex') active @endif @endisset">
                        <i class="fas fa-shopping-cart"></i>
                        <p>
                            فروشگاه
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{Url('admin/category')}}" class="nav-link @isset($page) @if($page =='categoryIndex') active @endif @endisset">
                                <i class="nav-icon  fas fa-list"></i>
                                <p>
                                    لیست گروه محصولات
                                </p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{Url('admin/product')}}" class="nav-link @isset($page) @if($page =='productIndex') active @endif @endisset">
                                <i class="nav-icon  fas fa-list"></i>
                                <p>
                                    لیست آگهی ها
                                </p>
                            </a>
                        </li>

                    </ul>
                </li>



                <li class="nav-item">
                    <a href="{{Url('admin/mailbox')}}" class="nav-link @isset($page) @if($page =='mailboxIndex') active @endif @endisset">
                        <i class="nav-icon far fa-envelope"></i>
                        <p>
                            صندوق پیام
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{Url('admin/files')}}" class="nav-link @isset($page) @if($page =='filemanager') active @endif @endisset">
                        <i class="nav-icon far fa-envelope"></i>
                        <p>
                            مدیریت فایل
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('logout')}}" class="nav-link" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        <i class="nav-icon  fas fa-sign-out-alt"></i>
                        <p>
                            خروج
                        </p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
