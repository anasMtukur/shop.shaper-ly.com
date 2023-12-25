<?php 
include "includes/inc-system-autoload.php"; 
include "includes/inc-header-main-session.php";

use SRC\DB\Entities\Client;
use SRC\Services\UI\Reports;
use SRC\DB\Entities\Product;
use SRC\Services\UI\UIFragments;
use SRC\DB\Entities\RegisteredUser;
use SRC\DB\Entities\ClientAccount;

$product   = new Product();

$clientId = 0;
if( isset( $_REQUEST['reference'] ) ) {
    $clientId = $_REQUEST['reference'];
}

$client = ( new Client() )->find( $clientId );

if(!$client){
    header( "Location: clients_manage.php?info=Item not found" );
    exit();
}

$reports = (new Reports())->getClientReports( $clientId );
$colors  = ['success', 'primary', 'danger', 'warning', 'info'];

$orders   = $reports["orders"];
$payments = $reports["payments"];

$account_balance = 0;
$account         = ( new ClientAccount() )->findBy( 'client', $clientId );
if( $account ){
    $account_balance = $account['balance'];
}

$totalOutstanding = $orders['total'] - $orders['discount'] - $payments['total'];
?>
<!DOCTYPE html>
<html lang="en">
    <?php include "includes/inc-header-main-tags.php" ?>
    <body class="sidebar-icon-only">
        <div class="container-scroller">
            <?php include "includes/inc-header-main-navbar.php" ?>

            <div class="container-fluid page-body-wrapper">

                <?php include "includes/inc-header-main-sidebar.php" ?>

                <div class="main-panel">
                    <div class="content-wrapper">
                        <?php include "includes/inc-alerts.php" ?>
                        <?php UIFragments::renderPageTitle("Client Reports", $client["name"], "Back", "href='clients_manage.php'") ?>
                        
                        <!-- Site Content -->
                        <div class="row"  id="printClosing">
                            <div class="col-md-12 grid-margin transparent">
                                <div class="row">
                                    <div class="col-md-3 mb-4 mb-lg-0 stretch-card transparent">
                                        <div class="card card-dark-blue">
                                            <div class="card-body">
                                                <p class="mb-4">Orders</p>
                                                <p class="fs-30 mb-2"><?= $orders['count'] ?></p>
                                                <p>No. of orders</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 stretch-card transparent">
                                        <div class="card card-tale">
                                            <div class="card-body">
                                                <p class="mb-4">Total Order Amount</p>
                                                <p class="fs-30 mb-2"><?= money_format( "&#8358; %!n", $orders['total'] ) ?></p>
                                                <p>Total amount in orders</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-4 mb-lg-0 stretch-card transparent">
                                        <div class="card card-light-blue">
                                            <div class="card-body">
                                                <p class="mb-4">Payments</p>
                                                <p class="fs-30 mb-2"><?= $payments['count'] ?></p>
                                                <p>No. of orders</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 stretch-card transparent">
                                        <div class="card card-light-danger">
                                            <div class="card-body">
                                                <p class="mb-4">Payment Total</p>
                                                <p class="fs-30 mb-2"><?= money_format( "&#8358; %!n", $payments['total'] ) ?></p>
                                                <p>Total amount in payments</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 d-flex align-items-center justify-content-between">
                                                <p class="text-primary fs-30 font-weight-medium p-10 mt-auto">Closing Statement</p>
                                                <button class="btn btn-icon btn-primary btn-md" onclick="printReportHandler( 'Client Report : <?= $client['name'] ?>' )">
                                                    <i class="icon-printer menu-icon"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="d-flex flex-wrap mb-1">
                                            <div class="mt-3 flex-grow-1">
                                                <p class="text-muted">Total Orders</p>
                                                <h3 class="text-primary fs-30 font-weight-medium">
                                                    <?= money_format( "&#8358; %!n", $orders['total'] ) ?>
                                                </h3>
                                            </div>
                                            <div class="mt-3 flex-grow-1">
                                                <p class="text-muted">Total Payment</p>
                                                <h3 class="text-primary fs-30 font-weight-medium">
                                                    <?= money_format( "&#8358; %!n", $payments['total'] ) ?>
                                                </h3>
                                            </div>
                                            <div class="mt-3 flex-grow-1">
                                                <p class="text-muted">Total Outstanding</p>
                                                <h3 class="text-primary fs-30 font-weight-medium">
                                                    <?= money_format( "&#8358; %!n", $totalOutstanding ) ?>
                                                </h3>
                                            </div>
                                            <div class="mt-3 flex-grow-1">
                                                <p class="text-muted">Total Discount</p>
                                                <h3 class="text-primary fs-30 font-weight-medium">
                                                    <?= money_format( "&#8358; %!n", $orders['discount'] ) ?>
                                                </h3>
                                            </div>
                                            <div class="mt-3 flex-grow-1">
                                                <p class="text-muted">Account Balance</p>
                                                <h3 class="text-primary fs-30 font-weight-medium">
                                                    <?= money_format( "&#8358; %!n", $account_balance ) ?>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 grid-margin transparent">
                                
                                <div class="demo-tabs p-0">
                                    <div style="background: #f5f7ff;"  id="printOrders" class="p-4" data-pws-tab="orders report" data-pws-tab-name="Order Reports">
                                        <div class="card"> 
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th width="4%">SNo.</th>
                                                                <!-- <th width="20%">Client</th> -->
                                                                <th class="text-center">Amount</th>
                                                                <th class="text-center">Discount</th>
                                                                <th class="text-center">Total</th>
                                                                <th class="text-center">Payment Status</th>
                                                                <th class="text-center">Payment Method</th>
                                                                <th class="text-center">Date</th>
                                                                <th class="text-right"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                            foreach ($orders['list'] as $key => $item) { 
                                                                $sno   = $key + 1;
                                                                $total = $item['amount'] - $item['discount'];
                                                            ?>
                                                                <tr>
                                                                    <td><?= $sno ?></td>
                                                                    <!-- <td><?= $client['name'] ?></td> -->
                                                                    <td class="text-center"><?= money_format( "&#8358; %!n", $item['amount'] ) ?></td>
                                                                    <td class="text-center"><?= money_format( "&#8358; %!n", $item['discount'] ) ?></td>
                                                                    <td class="text-center"><?= money_format( "&#8358; %!n", $total ) ?></td>
                                                                    <td class="text-center"><?= $item['pay_status'] ?></td>
                                                                    <td class="text-center"><?= $item['pay_method'] ?></td>
                                                                    <td class="text-center"><?= $item['created_at'] ?></td>
                                                                    <td class="text-right">
                                                                        <a href="orders_summary_order.php?reference=<?= $item['id'] ?>" target="blank">
                                                                            <button class="btn btn-icon btn-sm btn-outline-primary">
                                                                                <i class="fa fa-bar-chart"></i>
                                                                            </button>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-4" style="background: #f5f7ff;" id="printPayments" data-pws-tab="payment report" data-pws-tab-name="Payment Reports">
                                        <div class="card"> 
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12 col-xl-4 d-flex flex-column justify-content-start">
                                                        <div class="ml-xl-4 mt-3">
                                                            <p class="card-title"><?= $client["name"] ?></p>
                                                            <h1 class="text-primary"><?= money_format( "&#8358; %!n",  $reports['payments']['total'] ) ?></h1>
                                                            <h3 class="font-weight-500 mb-xl-4 text-primary">Total Payment Received</h3>
                                                            <p class="mb-2 mb-xl-0">
                                                                The total amount received in client payments. <br />
                                                                <strong><?= $reports['payments']['count'] ?></strong> Payments.
                                                            </p>
                                                        </div>  
                                                    </div>
                                                    <div class="col-md-12 col-xl-8">
                                                        <div class="row">
                                                            <div class="col-md-12 border-left">
                                                                <div class="table-responsive mb-3 mb-md-0 mt-3">
                                                                    <table class="table table-borderless report-table">
                                                                        <?php 
                                                                            $payMethods = ['Cash', 'POS', 'Transfer', 'Deposit'];
                                                                            foreach ( $payMethods as $index => $method ) {
                                                                                $payCatItem = $payments['methods'][$method];
                                                                                $color      = $colors[ $index ];
                                                                                $percent    = calculate_percentage_for_display( $payCatItem, $reports['payments']['total'] );
                                                                        ?>
                                                                                <tr>
                                                                                    <td class="text-muted"><?= $method ?></td>
                                                                                    <td class="w-100 px-0">
                                                                                        <div class="progress progress-md mx-4">
                                                                                            <div class="progress-bar bg-<?= $color ?>" role="progressbar" style="width: <?= $percent ?>" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td><h5 class="font-weight-bold mb-0"><?= money_format( "&#8358; %!n", $payCatItem ) ?></h5></td>
                                                                                </tr>
                                                                        <?php } ?>
                                                                        
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <!-- <div class="col-md-6 mt-3">
                                                                <canvas id="south-america-chart"></canvas>
                                                                <div id="south-america-legend"></div>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mt-4"> 
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th class="pl-0">Client</th>
                                                                <th>Payment Method</th>
                                                                <th class="text-center">Received By</th>
                                                                <th class="text-center">Amount</th>
                                                                <th class="text-right">Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                            foreach ($payments['list'] as $orderPayment){ 
                                                                $client = ( new Client() )->find( $orderPayment["client"] );
                                                                $user   = (new RegisteredUser())->find( $orderPayment["received_by"] );
                                                            ?>
                                                                <tr>
                                                                    <td class="pl-0"><?= $client["name"] ?></td>
                                                                    <td><?= $orderPayment["method"] ?></td>
                                                                    <td class="text-center"><?= $user["fullname"] ?></td>
                                                                    <td class="text-center"><?= $orderPayment["amount"] ?></td>
                                                                    <td class="text-right"><?= $orderPayment["created_at"] ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-4" style="background: #f5f7ff;" id="printExpenses"></div>
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
    </body>
</html>