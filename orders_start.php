<?php 
include "includes/inc-system-autoload.php";  
include "includes/inc-header-main-session.php";

use SRC\Services\UI\UIFragments;
use SRC\Helpers\CodeGenerator;
use SRC\DB\Entities\Client;
use SRC\DB\Entities\Order;

$order          = new Order();
$newOrderNumber = ( new CodeGenerator( $order ) )->randomCodeOfLength( 8 );
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
                        <?php UIFragments::renderPageTitle("Start New Order", "Select client to start new order.", "Manage Today's Orders", "href='orders_manage_day.php'") ?>
                        <!-- Site Content -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
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
                                        </div>
                                        <div class="card-footer bg-white d-flex justify-content-end" style="border-bottom-left-radius: 20%; border-bottom-right-radius: 20%; z-index: 9999;">
                                            <button type="submit" name="start_order" class="btn btn-primary mr-2">Start New Order</button>
                                            <a href="orders_manage.php" class="btn btn-outline-danger">Cancel</a>
                                        </div>
                                    </form>
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