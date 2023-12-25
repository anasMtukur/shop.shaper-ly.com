<?php 
include "includes/inc-system-autoload.php"; 
include "includes/inc-header-main-session.php";

use SRC\DB\Entities\DBEntity;
use SRC\DB\Entities\OrderItem;
use SRC\Services\UI\UIFragments;

$query = "SELECT sp_order_items.item, sp_order_items.`size`, SUM(sp_order_items.`quantity`) as quantity, SUM(sp_order_items.`amount`) as amount, sp_products.description FROM sp_order_items INNER JOIN sp_products ON sp_order_items.item=sp_products.id GROUP BY sp_order_items.item ORDER BY amount DESC";

$productSalesList = ( New OrderItem() )->query( $query );
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
                            <div class="col-12 mb-5">
                                <h3 class="font-weight-bold">
                                    Product Reports
                                </h3>
                                <h6 class="font-weight-normal mb-0">
                                    Product Sales Reports
                                </h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 grid-margin transparent">
                                <div class="card"> 
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th width="10%">SNo.</th>
                                                        <th width="60%">Product</th>
                                                        <th class="text-center">Quantity</th>
                                                        <th class="text-center">Amount</th>
                                                        <!-- <th class="text-right"></th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    foreach ($productSalesList as $key => $item) { 
                                                        $sno   = $key + 1;
                                                    ?>
                                                        <tr>
                                                            <td><?= $sno ?></td>
                                                            <td><?= $item['description'] ?></td>
                                                            <td class="text-center"><?= $item['quantity'] ?></td>
                                                            <td class="text-center"><?= money_format( "&#8358; %!n", $item['amount'] ) ?></td>
                                                            
                                                            <!-- <td class="text-right">
                                                                <a href="orders_summary_order.php?reference=<?= $item['id'] ?>" target="blank">
                                                                    <button class="btn btn-icon btn-sm btn-outline-primary">
                                                                        <i class="fa fa-bar-chart"></i>
                                                                    </button>
                                                                </a>
                                                            </td> -->
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

                    <?php include "includes/inc-footer-main.php" ?>
                </div>

            </div>
        </div>

        <?php include "includes/inc-footer-tags.php" ?>
    </body>
</html>