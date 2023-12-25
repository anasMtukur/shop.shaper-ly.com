<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
                <i class="menu-arrow"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="products_manage.php">
                <i class="icon-grid-2 menu-icon"></i>
                <span class="menu-title">Products</span>
                <i class="menu-arrow"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="clients_manage.php">
                <i class="icon-head menu-icon"></i>
                <span class="menu-title">Clients</span>
                <i class="menu-arrow"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="orders_start.php">
                <i class="icon-paper menu-icon"></i>
                <span class="menu-title">Orders</span>
                <i class="menu-arrow"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="outstanding_manage.php">
                <i class="icon-square-minus menu-icon"></i>
                <span class="menu-title">Outstanding</span>
                <i class="menu-arrow"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="expenses_manage.php">
                <i class="icon-circle-minus menu-icon"></i>
                <span class="menu-title">Expense Account</span>
                <i class="menu-arrow"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
                <i class="icon-bar-graph menu-icon"></i>
                <span class="menu-title">Reports</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="charts">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="reports_daily.php">
                            Today
                        </a>
                    </li>
                    <?php if( $logged_role === 'admin' ) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="reports_monthly.php">
                            Monthly Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reports_yearly.php">
                            Annual Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reports_periodic.php">
                            Periodic Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reports_product.php">
                            Product Reports
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </li>
        <?php if( $logged_role === 'admin' ) { ?>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#cperf" aria-expanded="false" aria-controls="charts">
                <i class="fa fa-line-chart menu-icon"></i>
                <span class="menu-title">Client Performance</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="cperf">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="cp_overall.php">
                            Overall Reports
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="cp_monthly.php">
                            Monthly Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cp_yearly.php">
                            Annual Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cp_periodic.php">
                            Periodic Reports
                        </a>
                    </li>
                    
                </ul>
            </div>
        </li>
        <?php } ?>
        <!-- <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                <i class="icon-grid-2 menu-icon"></i>
                <span class="menu-title">Products</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="tables">
                <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Basic table</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
                <i class="icon-contract menu-icon"></i>
                <span class="menu-title">Icons</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="icons">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Mdi icons</a></li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#error" aria-expanded="false" aria-controls="error">
            <i class="icon-ban menu-icon"></i>
            <span class="menu-title">Error pages</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="error">
            <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html"> 404 </a></li>
            <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> 500 </a></li>
            </ul>
        </div>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="pages/documentation/documentation.html">
            <i class="icon-paper menu-icon"></i>
            <span class="menu-title">Documentation</span>
        </a>
        </li> -->
        <li class="nav-item">
            <a class="nav-link" href="logout.php">
                <i class="icon-lock menu-icon text-danger"></i>
                <span class="menu-title">Logout</span>
            </a>
        </li>
    </ul>
</nav>