<aside class="main-sidebar elevation-4 sidebar-light-primary">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link navbar-white text-sm">
        <img src="{{ Url('images/icons/android-icon-48x48.png') }}"
             alt="Metroo Logo"
             class="brand-image1">
         <span class="brand-text font-weight-bold">جهان در قاب متـرو</span>

    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional)  <img src="{{ Url('images/icons/android-icon-48x48.png') }}" class="img-circle elevation-2" alt="User Image">-->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex nav nav-pills nav-sidebar flex-column text-sm">
            <a href="{{Url('dashboard/profile')}}" class="nav-link @isset($page) @if($page =='profile') active @endif @endisset">

            {{ Auth::user()->name }}<small> (ویرایش پروفایل)</small></a>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{Url('dashboard/product')}}" class="nav-link @isset($page) @if($page =='dashboard') active @endif @endisset">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            داشبورد
                        </p>
                    </a>
                </li>

                <li class="nav-item ">
                    <a href="{{Url('dashboard/product/create')}}" class="nav-link @isset($page) @if($page =='productCreate') active @endif @endisset">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            ثبت آگهی جدید
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{Url('dashboard/product')}}" class="nav-link @isset($page) @if($page =='productIndex') active @endif @endisset">
                        <i class="nav-icon  fas fa-list"></i>
                        <p>
                           لیست آگهی ها
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{Url('dashboard/mailbox')}}" class="nav-link @isset($page) @if($page =='mailboxIndex') active @endif @endisset">
                        <i class="nav-icon far fa-envelope"></i>
                        <p>
                           صندوق پیام
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
