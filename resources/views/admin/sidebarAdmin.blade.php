<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ Url('images/logo.png') }}"
             alt="Metroo Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text " style="font-size: 1.15rem;">{{ config('app.name', 'وب نت') }}</span>
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
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent  " data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item has-treeview  @isset($page) @if($page =='category-multimedia' or $page =='picture-multimedia' or $page =='sound-multimedia' or $page =='video-multimedia') menu-open @endif @endisset ">

                    <a href="#" class="nav-link  @isset($page) @if($page =='category-multimedia' or $page =='picture-multimedia' or $page =='sound-multimedia'or $page =='video-multimedia') active @endif @endisset">
                        <i class="fas fa-photo-video"></i>
                        <p>
                            چند رسانه
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{Url('admin/multimedia/categories')}}" class="nav-link @isset($page) @if($page =='category-multimedia') active @endif @endisset">
                                <i class="nav-icon  fas fa-stream"></i>
                                <p>
                                    لیست گروه چند رسانه
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{Url('admin/picture/create?t=multimedia')}}" class="nav-link @isset($page) @if($page =='picture-multimedia') active @endif @endisset">
                                <i class="nav-icon  fas fa-camera-retro  "></i>
                                <p>
                                     گالری تصویر جدید
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{Url('admin/picture/create?t=multimedia')}}" class="nav-link @isset($page) @if($page =='picture-multimedia') active @endif @endisset">
                                <i class="nav-icon  fas fa-camera-retro  "></i>
                                <p>
                                      لیست گالری تصویر
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{Url('admin/sound/create?t=multimedia')}}" class="nav-link @isset($page) @if($page =='sound-multimedia') active @endif @endisset">
                                <i class="nav-icon  fas fa-microphone-alt"></i>
                                <p>
                                     صوت جدید
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{Url('admin/video/create?t=multimedia')}}" class="nav-link @isset($page) @if($page =='video-multimedia') active @endif @endisset">
                                <i class="nav-icon  fas fa-film"></i>
                                <p>
                                     ویدیو جدید
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
