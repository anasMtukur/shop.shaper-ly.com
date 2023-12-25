<?php 
include "includes/inc-system-autoload.php"; 
include "includes/inc-header-main-session.php";

use SRC\Services\UI\UIFragments;
use SRC\DB\Entities\Expense;

$date      = date("Y/m/d");
$day_query = " WHERE `created_at` >= '" . $date . "' AND `created_at` < '" . $date . "' + INTERVAL 1 DAY";

$expenses = ( new Expense() )->getRows( $day_query );
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
                        <?php UIFragments::renderPageTitle("Expenses", "Manage all expenses!", "New Expense Entry", "href='expenses_new.php'") ?>
                        <!-- Site Content -->
                        <div class="row">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>SNo.</th>
                                                        <th>Description</th>
                                                        <th>Method</th>
                                                        <th>Amount</th>
                                                        <th>Created By</th>
                                                        <th>Created On</th>
                                                        <th class="text-center">
                                                            <i class="ti-settings"></i>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?= $expenses ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- <div class="card-footer bg-white">
                                        xx
                                    </div> -->
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