<?php 
include "includes/inc-system-autoload.php";  
include "includes/inc-header-main-session.php";

use SRC\Services\UI\UIFragments;
use SRC\DB\Entities\Order;
use SRC\DB\Entities\Client;

$LIMIT         = 10;
$LIMIT_OPTIONS = [10, 20, 50, 100, 500];
$REPORT_DATE   = date("Y/m/d");

if( isset( $_POST['report_date'] ) ) {
    $REPORT_DATE = $_POST['report_date'];
}


if(isset($_REQUEST["custom-limit"])){
    $LIMIT = $_REQUEST["custom-limit"];
}

$monthQ  = "WHERE MONTH(o.created_at) = MONTH('" . $REPORT_DATE . "')";
$q       = "SELECT c.id AS client_id, c.name AS client_name, SUM(o.amount) AS total_amount, SUM(o.total_paid) AS total_paid FROM sp_clients c JOIN sp_orders o ON c.id = o.client " . $monthQ . " GROUP BY c.id, c.name";
$q       = $q . " ORDER BY total_paid DESC LIMIT " . $LIMIT;
$list    = (new Client())->query( $q );

$timestamp = strtotime($REPORT_DATE);
$selected  = date("M, Y", $timestamp);
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
                        <div class="row">
                            <div class="col-md-12 grid-margin">
                                <div class="row">
                                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                                        <h3 class="font-weight-bold">
                                            Monthly Client Performance Report
                                        </h3>
                                        <h6 class="font-weight-normal mb-0">List of top <?= $LIMIT ?> clients based on total amount paid in <?= $selected ?></h6>
                                    </div>
                                    <div class="col-12 col-xl-2">
                                        <div class="justify-content-end d-flex">
                                            <form method="post" action="#">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                        </div>
                                                        <input type="date" class="form-control" name="report_date" aria-label="Date" required>
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
                                    <div class="col-12 col-xl-2">
                                        <div class="justify-content-end d-flex">
                                            <form method="post" action="#">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="hidden" name="report_date" value="<?= $REPORT_DATE ?>" required />
                                                        <select class="form-control" name="custom-limit" required>
                                                            <?php foreach( $LIMIT_OPTIONS as $opt ){ ?>
                                                            <option <?php if( $LIMIT == $opt ){ echo 'selected'; } ?>><?= $opt ?></option>
                                                            <?php } ?>
                                                        </select>
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
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Client</th>
                                                        <th class="text-center" width="40%">Total Amount</th>
                                                        <th class="text-center" width="40%">Total Paid</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php  
                                                    foreach ( $list as $item ) {  
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <strong class="text-primary">
                                                                    <?= $item["client_name"] ?>
                                                                </strong>
                                                            </td>
                                                            <td class="text-center">
                                                                <strong class="text-youtube">
                                                                    <?= money_format( "&#8358; %!n", $item["total_amount"] ) ?>
                                                                </strong>
                                                            </td>
                                                            <td class="text-center">
                                                                <strong class="text-success">
                                                                    <?= money_format( "&#8358; %!n", $item["total_paid"] ) ?>
                                                                </strong>
                                                            </td>
                                                            
                                                        </tr>
                                                                
                                                    <?php 
                                                    }
                                                    unset( $item );
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