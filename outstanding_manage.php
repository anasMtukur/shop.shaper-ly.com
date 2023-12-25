<?php 
include "includes/inc-system-autoload.php";  
include "includes/inc-header-main-session.php";

use SRC\Services\UI\UIFragments;
use SRC\DB\Entities\Order;
use SRC\DB\Entities\Client;

$client = new Client();
$order  = new Order();

$matches = [ 'is_deleted' => 0 ];
$clients = $client->list( $matches );
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
                        <?php UIFragments::renderPageTitleNoAction( 'Outstanding Client Payments', 'Clients with outstanding payments!' ) ?>

                        <!-- Site Content -->
                        <div class="row">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Client</th>
                                                        <th class="text-center" width="60%">Total</th>
                                                        <th class="text-right">
                                                            <i class="ti-settingsx"></i>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    foreach ( $clients as $key => $item ) { 
                                                        $q        = "SELECT SUM(`amount`) - SUM(`discount`) - SUM(`total_paid`) as outstanding FROM `sp_orders` WHERE (`pay_status` = 'Waiting' OR `pay_status` = 'Partly Paid') AND `client` = " . $item["id"];
                                                        $os       = $order->query( $q );
                                                        if( count( $os ) > 0 ) {
                                                            $total_os = current( $os )['outstanding'];
                                                            if( $total_os > 0 ){
                                                                $matches  = " WHERE `client` = '" . $item["id"] . "' AND (`pay_status` = 'Partly Paid' OR `pay_status` = 'Waiting')";
                                                                $orders   = $order->getList( $matches, true );
                                                    ?>
                                                                <tr>
                                                                    <td>
                                                                        <strong class="text-primary">
                                                                            <?= $item["name"] ?>
                                                                        </strong>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <strong class="text-youtube">
                                                                            <?= money_format( "&#8358; %!n", $total_os ) ?>
                                                                        </strong>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <button class="btn btn-icon btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#collapse<?= $item['id'] ?>" aria-expanded="false" aria-controls="collapseExample">
                                                                            <i class="ti-angle-down"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <tr class="collapse" id="collapse<?= $item['id'] ?>">
                                                                    <td colspan="3" class="p-0">
                                                                        <div class="card p-0">
                                                                            <div class="card-body p-0">
                                                                                <div class="table-responsive  p-0">
                                                                                    <table class="table table-striped">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>Order Number</th>
                                                                                                <th class="text-center" width="25%">Total</th>
                                                                                                <th class="text-center" width="20%">Paid</th>
                                                                                                <th class="text-center" width="20%">Outstanding</th>
                                                                                                <?php if( $logged_role === 'admin' ) { ?>
                                                                                                <th class="text-right">
                                                                                                    <i class="ti-settingsx"></i>
                                                                                                </th>
                                                                                                <?php } ?>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php 
                                                                                                foreach ( $orders as $orderItem ){
                                                                                                    $total = $orderItem["amount"] - $orderItem["discount"];
                                                                                                    $paid  = $orderItem["total_paid"];
                                                                                                    $outs  = $total - $paid;
                                                                                            ?>
                                                                                                <tr>
                                                                                                    <td><?= $orderItem["order_no"] ?></td>
                                                                                                    <td class="text-center"><?= money_format( "&#8358; %!n", $total ) ?></td>
                                                                                                    <td class="text-center"><?= money_format( "&#8358; %!n", $paid ) ?></td>
                                                                                                    <td class="text-center"><?= money_format( "&#8358; %!n", $outs ) ?></td>
                                                                                                    <?php if( $logged_role === 'admin' ) { ?>
                                                                                                    <td class="text-right">
                                                                                                        <a href="orders_manage_order.php?reference=<?= $orderItem["id"] ?>">
                                                                                                            <button type="button" class="btn btn-rounded btn-icon btn-sm btn-outline-primary"><i class="ti-menu-alt"></i></button>
                                                                                                        </a>
                                                                                                    </td>
                                                                                                    <?php } ?>
                                                                                                </tr>
                                                                                            <?php 
                                                                                                } 
                                                                                                unset( $orderItem )
                                                                                            ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                    <?php 
                                                            }
                                                        }
                                                        unset( $item );
                                                        unset( $orders );
                                                        unset( $matches );
                                                    } 
                                                    ?>
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