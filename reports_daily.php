<?php 
include "includes/inc-system-autoload.php"; 
include "includes/inc-header-main-session.php";

use SRC\DB\Entities\Client;
use SRC\Services\UI\Reports;
use SRC\DB\Entities\Product;
use SRC\DB\Entities\RegisteredUser;

$product   = new Product();
$reportDate = date("Y/m/d");
if( isset( $_POST['generate'] ) ) {
    $reportDate = $_POST['report_date'];
}

$reports = (new Reports())->getDailyReports( $reportDate );
$colors  = ['success', 'primary', 'danger', 'warning', 'info'];

$orders   = $reports["orders"];
$payments = $reports["payments"];
$expenses = $reports["expenses"];

$totalOutstanding = $orders['total'] - $orders['discount'] - $payments['total'];
$totalReceived    = $payments['total'] - $expenses['total'];
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
                        <div class="row">
                            <div class="col-md-12 grid-margin">
                                <div class="row">
                                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                                        <h3 class="font-weight-bold">
                                            Daily Reports
                                        </h3>
                                        <h6 class="font-weight-normal mb-0"><?= date( "D, d/m/Y", strtotime( $reportDate ) ) ?></h6>
                                    </div>
                                    <div class="col-12 col-xl-4">
                                        <div class="justify-content-end d-flex">
                                            <form method="post" action="#">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                        </div>
                                                        <input type="date" class="form-control" name="report_date" aria-label="Date" required />
                                                        <div class="input-group-append">
                                                            <button type="submit" name="generate" value="submit" style="height: auto;" class="btn btn-icon btn-primary btn-md">
                                                                <i class="icon-bar-graph menu-icon"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <!-- Site Content -->
                        
                        <div class="row">
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

                        <div class="row" id="printClosing">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 d-flex align-items-center justify-content-between">
                                                <p class="text-primary fs-30 font-weight-medium p-10 mt-auto">Closing Statement</p>
                                                <button class="btn btn-icon btn-primary btn-md" onclick="printReportHandler( 'Daily Report : <?= date( 'D, d/m/Y', strtotime( $reportDate ) ) ?>' )">
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
                                                <p class="text-muted">Total Discount</p>
                                                <h3 class="text-primary fs-30 font-weight-medium">
                                                    <?= money_format( "&#8358; %!n", $orders['discount'] ) ?>
                                                </h3>
                                            </div>
                                            <div class="mt-3 flex-grow-1">
                                                <p class="text-muted">Total Outstanding</p>
                                                <h3 class="text-primary fs-30 font-weight-medium">
                                                    <?= money_format( "&#8358; %!n", $totalOutstanding ) ?>
                                                </h3>
                                            </div>
                                            <div class="mt-3 flex-grow-1">
                                                <p class="text-muted">Total Expenses</p>
                                                <h3 class="text-primary fs-30 font-weight-medium">
                                                    <?= money_format( "&#8358; %!n", $expenses['total'] ) ?>
                                                </h3>
                                            </div> 
                                            <div class="mt-3 flex-grow-1">
                                                <p class="text-muted">Total Received</p>
                                                <h3 class="text-primary fs-30 font-weight-medium">
                                                    <?= money_format( "&#8358; %!n", $totalReceived ) ?>
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
                                                                <th width="8%">SNo.</th>
                                                                <th width="20%">Client</th>
                                                                <th class="text-center">Amount</th>
                                                                <th class="text-center">Discount</th>
                                                                <th class="text-center">Total</th>
                                                                <th class="text-center">Payment Status</th>
                                                                <th class="text-center">Payment Method</th>
                                                                <th class="text-right">Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                            foreach ($orders['list'] as $key => $item) { 
                                                                $client = ( new Client() )->find( $item["client"] );
                                                                $sno    = $key + 1;
                                                                $total  = $item["amount"] - $item["discount"];
                                                            ?>
                                                                <tr>
                                                                    <td><?= $sno ?></td>
                                                                    <!-- <td><?= $item['order_no'] ?></td> -->
                                                                    <td><?= $client['name'] ?></td>
                                                                    <td class="text-center"><?= money_format( "&#8358; %!n", $item['amount'] ) ?></td>
                                                                    <td class="text-center"><?= money_format( "&#8358; %!n", $item['discount'] ) ?></td>
                                                                    <td class="text-center"><?= money_format( "&#8358; %!n", $total ) ?></td>
                                                                    <td class="text-center"><?= $item['pay_status'] ?></td>
                                                                    <td class="text-center"><?= $item['pay_method'] ?></td>
                                                                    <td class="text-right"><?= $item['created_at'] ?></td>
                                                                </tr>
                                                                                                                                
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-4" style="background: #f5f7ff;"  id="printPayments"  data-pws-tab="payment report" data-pws-tab-name="Payment Reports">
                                        <div class="card"> 
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12 col-xl-4 d-flex flex-column justify-content-start">
                                                        <div class="ml-xl-4 mt-3">
                                                            <p class="card-title"><?= date( "D, d/m/Y", strtotime( $reportDate ) ) ?></p>
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
                                    <div class="p-4" style="background: #f5f7ff;" id="printExpenses"  data-pws-tab="expense report" data-pws-tab-name="Expense Reports">
                                        <div class="card"> 
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12 col-xl-4 d-flex flex-column justify-content-start">
                                                        <div class="ml-xl-4 mt-3">
                                                            <p class="card-title"><?= date( "D, d/m/Y", strtotime( $reportDate ) ) ?></p>
                                                            <h1 class="text-primary"><?= money_format( "&#8358; %!n",  $expenses['total'] ) ?></h1>
                                                            <h3 class="font-weight-500 mb-xl-4 text-primary">Total Expenses Spent</h3>
                                                            <p class="mb-2 mb-xl-0">
                                                                The total amount spent in expenses. <br />
                                                                <strong><?= $expenses['count'] ?></strong> Expenses.
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
                                                                                $payCatItem = $expenses['methods'][$method];
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
                                                                <th class="pl-0">Description</th>
                                                                <th>Payment Method</th>
                                                                <th class="text-center">Created By</th>
                                                                <th class="text-center">Amount</th>
                                                                <th class="text-right">Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                            foreach ($expenses['list'] as $expenseItem){ 
                                                                $user   = (new RegisteredUser())->find( $expenseItem["created_by"] );
                                                            ?>
                                                                <tr>
                                                                    <td class="pl-0"><?= $expenseItem["description"] ?></td>
                                                                    <td><?= $expenseItem["method"] ?></td>
                                                                    <td class="text-center"><?= $user["fullname"] ?></td>
                                                                    <td class="text-center"><?= $expenseItem["amount"] ?></td>
                                                                    <td class="text-right"><?= $expenseItem["created_at"] ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
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
        
    </body>
</html>