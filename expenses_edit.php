<?php 
include "includes/inc-system-autoload.php"; 
include "includes/inc-header-main-session.php";

use SRC\Services\UI\UIFragments;
use SRC\DB\Entities\Expense;

$item_id = '0';
if( isset( $_REQUEST['reference'] ) ){
    $item_id = $_REQUEST['reference'];
}

$item = ( new Expense() )->find( $item_id );

if(!$item){
    header( "Location: expenses_manage.php?info=Item not found" );
    exit();
}
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
                        <?php UIFragments::renderPageTitle("Expenses", "Add New Expense!", "Cancel", "href='clients_manage.php'") ?>
                        <!-- Site Content -->
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                    
                                        <form class="forms-sample" method="POST" action="expenses_action.php">
                                            <input type="hidden" name="reference" class="form-control" id="exampleInputId1" value="<?= $item["id"] ?>">
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Description</label> 
                                                <input type="text" class="form-control" name="description" value="<?= $item["description"] ?>" required />
                                            </div>

                                            <div class="form-group">
                                                <label for="inputPaidAmount">Amount</label>
                                                <input type="number" name="amount" class="form-control" id="inputPaidAmount" value="<?= $item["amount"] ?>" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="exampleSelectMethod">Method of Expense</label>
                                                <select required name="method" class="form-control" id="exampleSelectMethod">
                                                    <option value="">Select Method of Expense</option>
                                                    <option <?= $item['method'] === 'Cash' ? 'selected' : '' ?> value="Cash">Cash</option>
                                                    <option <?= $item['method'] === 'POS' ? 'selected' : '' ?> value="POS">POS</option>
                                                    <option <?= $item['method'] === 'Transfer' ? 'selected' : '' ?> value="Transfer">Transfer</option>
                                                </select>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-sm-12 d-flex align-item-center justify-content-end">
                                                    <button type="submit" name="update_expense" class="btn btn-outline-primary mr-2">Submit</button>
                                                    <a href="expenses_manage.php" class="btn btn-outline-danger">Cancel</a>
                                                </div>
                                            </div>
                                        </form>

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