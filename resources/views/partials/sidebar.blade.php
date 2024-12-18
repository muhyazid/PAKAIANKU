<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo" href="/dashboard" style="color: white;">APPAREL</a>
    </div>
    <ul class="nav">
        <li class="nav-item profile">
            <div class="profile-desc">
                <div class="profile-pic">
                    <div class="count-indicator">
                        <img class="img-xs rounded-circle " src="{{ asset('assets/images/faces/face15.jpg') }}"
                            alt="">
                        <span class="count bg-success"></span>
                    </div>
                    <div class="profile-name">
                        <h5 class="mb-0 font-weight-normal">Masbro</h5>
                        <span>Admin</span>
                    </div>
                </div>
                <a href="#" id="profile-dropdown" data-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
                <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list"
                    aria-labelledby="profile-dropdown">
                    <a href="#" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-settings text-primary"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1 text-small">Account settings</p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-onepassword  text-info"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1 text-small">Change Password</p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-calendar-today text-success"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1 text-small">To-do list</p>
                        </div>
                    </a>
                </div>
            </div>
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">Navigation</span>
        </li>
        <li class="nav-item menu-items {{ Request::is('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/dashboard') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-speedometer"></i>
                </span>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item menu-items {{ Request::is('products*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('products.index') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-playlist-play"></i>
                </span>
                <span class="menu-title">Produk</span>
            </a>
        </li>

        <li class="nav-item menu-items {{ Request::is('suppliers*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('suppliers.index') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-playlist-play"></i>
                </span>
                <span class="menu-title">Suppliers</span>
            </a>
        </li>

        <li class="nav-item menu-items {{ Request::is('material*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('materials.index') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-table-large"></i>
                </span>
                <span class="menu-title">Material</span>
            </a>
        </li>
        <li class="nav-item menu-items {{ Request::is('customers*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('customers.index') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-account-multiple"></i>
                </span>
                <span class="menu-title">Customers</span>
            </a>
        </li>
        <li class="nav-item menu-items {{ Request::is('boms*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('boms.index') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-cloud-print-outline"></i>
                </span>
                <span class="menu-title">BoM</span>
            </a>
        </li>

        <!-- Tambahan menu Manufacturing Order -->
        <li class="nav-item menu-items {{ Request::is('manufacturing_orders*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('manufacturing_orders.index') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-factory"></i>
                </span>
                <span class="menu-title">Manufacturing Order</span>
            </a>
        </li>


        <li class="nav-item menu-items {{ Request::is('rfq*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('rfq.index') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-playlist-play"></i>
                </span>
                <span class="menu-title">Purchase</span>
            </a>
        </li>



        <li class="nav-item menu-items {{ Request::is('sales*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('sales.index') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-cart"></i>
                </span>
                <span class="menu-title">Sales</span>
            </a>
        </li>


        <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <span class="menu-icon">
                    <i class="mdi mdi-security"></i>
                </span>
                <span class="menu-title">User Pages</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/blank-page.html"> Blank Page </a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html"> 404 </a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> 500 </a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Login </a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Register </a></li>
                </ul>
            </div>
        </li>

    </ul>
</nav>
