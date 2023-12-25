<?php 
include "includes/inc-system-autoload.php";  
include "includes/inc-header-main-session.php";

use SRC\Services\UI\UIFragments;
use SRC\DB\Entities\Order;

$date      = date("Y/m/d");
$day_query = " WHERE `created_at` >= '" . $date . "' AND `created_at` < '" . $date . "' + INTERVAL 1 DAY";

$order  = new Order();
$orders = $order->getList( $day_query ); 
?>

<!DOCTYPE html>
<html lang="en">
    <?php include "includes/inc-header-main-tags.php" ?>
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <style>
        .select2-selection {
            padding: 0 !important;

        }
    </style>
    <body class="sidebar-icon-only">
        <div class="container-scroller">
            <?php include "includes/inc-header-main-navbar.php" ?>

            <div class="container-fluid page-body-wrapper">

                <?php include "includes/inc-header-main-sidebar.php" ?>

                <div class="main-panel">
                    <div class="content-wrapper">
                        <?php include "includes/inc-alerts.php" ?>
                        <?php UIFragments::renderPageTitle("Today's Orders", 'Manage all orders today!', 'Manage Order History', 'href="orders_manage.php"') ?>

                        <!-- Site Content -->
                        <div class="row">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Order Number</th>
                                                        <th>Client</th>
                                                        <!-- <th class="text-center">Amount</th>
                                                        <th class="text-center">Discount</th> -->
                                                        <th class="text-center">Total</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-right">
                                                            <i class="ti-settingsx"></i>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?= $orders['rows'] ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Site Content -->
                    </div>

                    <?php include "includes/inc-footer-main.php" ?>
                </div>

            </div>
        </div>

        <?php include "includes/inc-footer-tags.php" ?>
        <script src="vendors/select2/select2.min.js"></script>
        <script src="js/select2.js"></script>
    </body>
</html>