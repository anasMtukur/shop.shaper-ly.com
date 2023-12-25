<?php 
include "includes/inc-system-autoload.php"; 
include "includes/inc-header-main-session.php";

use SRC\DB\Entities\Client;
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
                        <?php include "includes/dashboard/inc-admin-dashboard-title.php" ?>
                        <!-- Admin Site Content -->
                        <?php if( $logged_role === 'admin' ) { ?>
                                <?php include "includes/dashboard/inc-admin-dashboard-counters.php" ?>

                                <?php include "includes/dashboard/inc-admin-dashboard-report.php" ?>
                        <?php } ?>
                        <!-- /Admin Site Content -->

                        <!-- Manager Site Content -->
                        <?php if( $logged_role === 'manager' ) { ?>
                            
                            <div class="row">
                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Start New Order</h4>
                                            <p class="card-description">
                                                Please select a client to start a new order
                                            </p>
                                            
                                            <form method="post" action="orders_action.php">
                                                <div class="card-body">
                                                    <input type="hidden" name="order_no" value="<?= $newOrderNumber ?>" />

                                                    <div class="form-group">
                                                        <label>Select Client for new order</label>
                                                        <select name="client" required class="js-example-basic-single w-100" style="width: 100%; margin-top: 10px;">
                                                            <option value="">Select Client</option>
                                                            <?= ( new Client() )->getClientSelectOptions( [ 'is_deleted' => [ 'value' => '0', 'link' => 'and'] ] ) ?>
                                                        </select>
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <button type="submit" name="start_order" class="btn btn-outline-primary">Start New Order</button>
                                                    </div>
                                                </div>
                                                
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 grid-margin">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">New Expenses</h4>
                                            <p class="card-description">
                                                Add New Expenses
                                            </p>

                                            <form class="forms-sample" method="POST" action="expenses_action.php">
                                                <div class="form-group">
                                                    <label for="exampleInputUsername1">Description</label> 
                                                    <input type="text" class="form-control" name="description" placeholder="Expense Description">
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="inputPaidAmount">Amount</label>
                                                    <input type="number" name="amount" class="form-control" id="inputPaidAmount" placeholder="Amount">
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="exampleSelectMethod">Method of Expense</label>
                                                    <select required name="method" class="form-control" id="exampleSelectMethod">
                                                        <option value="">Select Method of Expense</option>
                                                        <option value="Cash">Cash</option>
                                                        <option value="POS">POS</option>
                                                        <option value="Transfer">Transfer</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-sm-12 d-flex align-item-center justify-content-end">
                                                        <button type="submit" name="add_expense" class="btn btn-outline-primary">Add New Expense</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- /Manager Site Content -->

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