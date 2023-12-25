<?php 
include "includes/inc-system-autoload.php";  
include "includes/inc-header-main-session.php";

use SRC\Services\UI\UIFragments;
use SRC\Helpers\AddressHelper;
use SRC\DB\Entities\Order;
use SRC\DB\Entities\Client;
use SRC\DB\Entities\Product;
use SRC\DB\Entities\OrderItem;
use SRC\DB\Entities\Payment;
use SRC\DB\Entities\RegisteredUser;

$product    = new Product();
$orderItem  = new OrderItem();

$user_id = $logged_account['id'];
$order_id = '0';
$total = 0;
if( isset( $_REQUEST['reference'] ) ){
    $order_id = $_REQUEST['reference'];
}

$order = ( new Order() )->find( $order_id );
if(!$order){
    header( "Location: orders_manage.php?info=Item not found" );
    exit();
}

$client_id = $order["client"];
$client    = ( new Client() )->find( $client_id );

$orderItems = (new OrderItem())->list([
    'order_id' => [
        'link'  => 'AND',
        'value' => $order_id
    ],
]);

$orderPayments = (new Payment())->list([
    'order_id' => [
        'link'  => 'AND',
        'value' => $order_id
    ],
]);

$discount = $order[ 'discount' ];
?>
<!DOCTYPE html>
<html lang="en">
    <?php include "includes/inc-header-main-tags.php" ?>
    <link rel="stylesheet" href="css/invoice.css">
    <body class="sidebar-icon-only">
        <div class="container-scroller">
            <?php include "includes/inc-header-main-navbar.php" ?>

            <div class="container-fluid page-body-wrapper">

                <?php include "includes/inc-header-main-sidebar.php" ?>

                <div class="main-panel">
                    <div class="content-wrapper">
                        <?php include "includes/inc-alerts.php" ?>
                        <?php UIFragments::renderPageTitle( 'Order Details', $order["order_no"] . '<label class="badge badge-warning ml-2">' . $order["status"] . '</label>', "Close", "href='orders_manage.php'") ?>
                    
                        <!-- Site Content -->
                        <div class="row">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="invoice-company text-inverse f-w-600">
                                                    <span class="pull-right hidden-print">
                                                        <button data-toggle="modal" data-target="#exampleModal" class="btn btn-sm btn-white m-b-10 p-l-5 mr-2">
                                                            <i class="fa fa-money t-plus-1 fa-fw fa-lg"></i>
                                                            Accept Payment
                                                        </button>
                                                        <button id="print-div" onclick="printDivHandler( 'printable', 'Order Invoice : <?= $order['order_no'] ?>' )" class="btn btn-sm btn-white m-b-10 p-l-5"><i class="fa fa-print t-plus-1 fa-fw fa-lg"></i> Print</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12" id="printable">
                                                <div class="invoice">
                                                    <!-- begin invoice-company -->
                                                    <div class="invoice-company text-inverse w-100 text-center">
                                                        <img src="images/logo.png" style="width:70px;" alt="logo"/>
                                                        <h4 class="text-center mt-2">Shaperly Photography Services</h4>
                                                    </div>
                                                    <!-- end invoice-company -->
                                                    <!-- begin invoice-header -->
                                                    <div class="invoice-header">
                                                        <div class="invoice-from">
                                                            <address class="m-t-5 m-b-5">
                                                                    <strong class="text-inverse">
                                                                        <?= $client["name"] ?>
                                                                    </strong><br>
                                                                    <span class="mb-1">
                                                                        <?= AddressHelper::getAddressFromJson( $client["address"] ) ?>
                                                                    </span><br>
                                                                    Phone: <?= $client["mobile"] ?><br>
                                                                    Email: <?= $client["email"] ?>
                                                            </address>
                                                        </div>
                                                        
                                                        <div class="invoice-date">
                                                            <strong>Order Summary</strong>
                                                            <div class="date text-inverse m-t-5">
                                                                <?= $order["created_at"] ?>
                                                            </div>
                                                            <div class="invoice-detail">
                                                                #<?= $order["order_no"] ?><br>
                                                                Services Product
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- end invoice-header -->
                                                    <!-- begin invoice-content -->
                                                    <div class="card-header">
                                                        <h4 class="card-title mb-0">Products</h4>
                                                    </div>
                                                    <div class="invoice-content">
                                                        <!-- begin table-responsive -->
                                                        <div class="table-responsive">
                                                            <table class="table table-invoice">
                                                                <thead>
                                                                    <tr>
                                                                        <th>DESCRIPTION</th>
                                                                        <th class="text-center" width="10%">SIZE</th>
                                                                        <th class="text-center" width="10%">QTY</th>
                                                                        <th class="text-center" width="10%">UNIT</th>
                                                                        <th class="text-right" width="20%">TOTAL</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php 
                                                                    foreach ($orderItems as $orderItem){ 
                                                                        $total += (float) $orderItem['amount'];
                                                                        $itemProduct = $product->find( $orderItem["item"] );
                                                                    ?>
                                                                        <tr>
                                                                            <td>
                                                                                <span class="text-inverse">
                                                                                    <?= $itemProduct["description"] ?>
                                                                                </span><br>
                                                                                <small>
                                                                                    <?= $orderItem["quantity"] ?> of <?= $orderItem["size"] ?>
                                                                                </small>
                                                                            </td>
                                                                            <td class="text-center"><?= $orderItem["size"] ?></td>
                                                                            <td class="text-center"><?= $orderItem["quantity"] ?></td>
                                                                            <td class="text-center"><?= money_format( "&#8358; %!n", $orderItem["price"] ) ?></td>
                                                                            <td class="text-right"><?= money_format( "&#8358; %!n", $orderItem["amount"] ) ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- end table-responsive -->

                                                        <div class="row">
                                                            <div class="col-lg-12 grid-margin stretch-card">
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <h4 class="card-title mb-0">Payment Received</h4>
                                                                    </div>
                                                                    <div class="card-body" style="padding: 0;">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-striped">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Payment Method</th>
                                                                                        <th class="text-center" width="10%">Received By</th>
                                                                                        <th class="text-center" width="10%">Amount</th>
                                                                                        <th class="text-right" width="20%">Date</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php 
                                                                                    $totalPayment = 0;
                                                                                    foreach ($orderPayments as $orderPayment){ 
                                                                                        $totalPayment += (float) $orderPayment['amount'];
                                                                                        $user = (new RegisteredUser())->find( $orderPayment["received_by"] );
                                                                                    ?>
                                                                                        <tr>
                                                                                            <td><?= $orderPayment["method"] ?></td>
                                                                                            <td class="text-center"><?= $user["fullname"] ?></td>
                                                                                            <td class="text-center"><?= money_format( "&#8358; %!n", $orderPayment["amount"] ) ?></td>
                                                                                            <td class="text-right"><?= $orderPayment["created_at"] ?></td>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php 
                                                            $subtotal    = $total - $discount;
                                                            $outstanding = $subtotal - $totalPayment;
                                                        ?>
                                                        <!-- begin invoice-price -->
                                                        <div class="invoice-price">
                                                            <div class="invoice-price-left">
                                                                <div class="invoice-price-row">
                                                                    <div class="sub-price">
                                                                        <small>SUBTOTAL</small>
                                                                        <span class="text-inverse"><?= money_format( "&#8358; %!n", $total ) ?></span>
                                                                    </div>
                                                                    <div class="sub-price">
                                                                        <i class="fa fa-minus text-muted"></i>
                                                                    </div>
                                                                    <div class="sub-price">
                                                                        <small>DISCOUNT</small>
                                                                        <span class="text-inverse"><?= money_format( "&#8358; %!n", $discount ) ?></span>
                                                                    </div>
                                                                    <div class="sub-price">
                                                                        <i class="fa fa-minus text-muted"></i>
                                                                    </div>
                                                                    <div class="sub-price">
                                                                        <small>PAID</small>
                                                                        <span class="text-inverse"><?= money_format( "&#8358; %!n", $totalPayment ) ?></span>
                                                                    </div>
                                                                    <div class="sub-price">
                                                                        <i class="fa  fa-angle-double-right text-muted"></i>
                                                                    </div>
                                                                    <div class="sub-price">
                                                                        <small>OUTSTANDING</small>
                                                                        <span class="text-inverse"><?= money_format( "&#8358; %!n", $outstanding ) ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="invoice-price-right">
                                                                <small>TOTAL</small> <span class="f-w-600"><?= money_format( "&#8358; %!n", $subtotal ) ?></span>
                                                            </div>
                                                        </div>
                                                        <!-- end invoice-price -->
                                                    </div>
                                                    <!-- end invoice-content -->
                                                    <!-- begin invoice-note -->
                                                    <div class="invoice-note">
                                                        * Make all cheques payable to [Your Company Name]<br>
                                                        * Payment is due within 30 days<br>
                                                        * If you have any questions concerning this invoice, contact us @ printshaperly@gmail.com
                                                    </div>
                                                    <!-- end invoice-note -->
                                                    <!-- begin invoice-footer -->
                                                    <div class="invoice-footer">
                                                        <p class="text-center m-b-5 f-w-600">
                                                        THANK YOU FOR YOUR BUSINESS
                                                        </p>
                                                        <p class="text-center">
                                                        <span class="m-r-10"><i class="fa fa-fw fa-lg fa-globe"></i> www.shaper-ly.com</span>
                                                        <span class="m-r-10"><i class="fa fa-fw fa-lg fa-phone"></i> T:08085790806</span>
                                                        <span class="m-r-10"><i class="fa fa-fw fa-lg fa-envelope"></i> printshaperly@gmail.com</span>
                                                        </p>
                                                    </div>
                                                    <!-- end invoice-footer -->
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

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Accept Order Payment</h5>
                        
                    </div>

                    <form method="POST" action="orders_action.php">
                        <input type="hidden" name="order_id" value="<?= $order_id ?>" />
                        <input type="hidden" name="user_id" value="<?= $user_id ?>" />
                        <input type="hidden" name="client_id" value="<?= $client_id ?>" />
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="inputPaidAmount">Paid Amount</label>
                                <input type="number" name="amount" class="form-control" id="inputPaidAmount" placeholder="Paid Amount">
                            </div>
                            
                            <div class="form-group">
                                <label for="exampleSelectMethod">Method of Payment</label>
                                <select required name="pay_method" class="form-control" id="exampleSelectMethod">
                                    <option value="">Select Method of Payment</option>
                                    <option value="Cash">Cash</option>
                                    <option value="POS">POS</option>
                                    <option value="Transfer">Transfer</option>
                                    <option value="Deposit">Direct Deposit</option>
                                    <option value="Account">Client Account</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="accept_payment" value="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <?php include "includes/inc-footer-tags.php" ?>
    </body>
</html>