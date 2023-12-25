<?php 
include "includes/inc-system-autoload.php";  
include "includes/inc-header-main-session.php";

use SRC\Services\UI\UIFragments;
use SRC\DB\Entities\Order;
use SRC\DB\Entities\Client;
use SRC\DB\Entities\Product;
use SRC\DB\Entities\OrderItem;
use SRC\DB\Entities\Payment;
use SRC\DB\Entities\RegisteredUser;
use SRC\DB\Entities\ClientAccount;

$user_id = $logged_account['id'];
$order_id = '0';
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

$account = ( new ClientAccount() )->findBy( 'client', $client_id );

//Products For Selection
$matches    = [ 'is_deleted' => [
    'link' => 'AND',
    'value' => '0',
] ];
$pages      = [];
$page       = 1;
$cp         = 1;
$size       = 100;
$offsetDate = '2023-01-01 00:00:01';
$offsetId   = '1';
$nextLink   = '#';
$prevLink   = '#';

if ( isset( $_REQUEST['offset_id'] ) ) {
	$offsetId = $_REQUEST['offset_id'];
}

if ( isset( $_REQUEST['offset_date'] ) ) {
	$offsetDate = $_REQUEST['offset_date'];
}

if ( isset( $_REQUEST['move'] ) ) {
	$move = $_REQUEST['move'];
	$cp   = isset( $_REQUEST['current_page'] ) ? $_REQUEST['current_page'] : 1;
	if( $move === 'next' ) {
		$page = (int) $cp + 1;
	}

	if( $move === 'prev' ) {
		$page = (int) $cp - 1;
	}
}

if( isset( $_SESSION["wbs_order_product_pages"] ) ) {
	$pages = $_SESSION["wbs_order_product_pages"];
	if( !array_key_exists( $page, $pages ) ) {
		$pages[$page] = [
			'id'   => $offsetId,
  			'date' => $offsetDate,
		];
	}
	$_SESSION["wbs_order_product_pages"] = $pages;
}else{
	$_SESSION["wbs_order_product_pages"] = [
		$page => [
			'id'   => $offsetId,
  			'date' => $offsetDate,
		],
	];
}

$product    = new Product();
$products   = $product->getProductList( $size, $offsetId, $offsetDate, true, $matches, false, $order_id ); 
$next       = $products["offset"];
$checkPages = round( ( (int)$products["count"] / $size ) );
$totalPages = $checkPages >= 1 ? $checkPages : 1;

$pages = $_SESSION["wbs_order_product_pages"];
if( $page > 1 ) {
	$prev  = $pages[ $page - 1 ];
	$prevLink = '?reference=' . $order_id . '&current_page=' . $page . '&move=prev&offset_id=' .$prev["id"]. '&offset_date=' . $prev["date"];
}

$nextAllowed = (($page) * $size) - (int)$products['count'];
if( $nextAllowed < 0) {
	$nextLink = '?reference=' . $order_id . '&current_page=' . $page . '&move=next&offset_id=' .$next["id"]. '&offset_date=' . $next["date"];
}


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

$outstanding = 0;
$totalPayment = 0;
$discount = $order['discount'];

$close = $_SERVER['HTTP_REFERER'];
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
                        <?php UIFragments::renderPageTitle( $client["name"], $order["order_no"] . '<label class="badge badge-warning ml-2">' . $order["status"] . '</label>', "Close", "href='" . $close . "'") ?>
                    
                        <!-- Site Content -->
                        <div class="row">
                            <?php if ($order["status"] === 'New') { ?>
                            <div class="col-lg-7 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Product Description</th>
                                                        <th class="text-right">
                                                            <i class="ti-settings"></i>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?= $products['rows'] ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <div class="row">
                                            <div class="col-sm-12 d-flex align-items-center justify-content-between">
                                                <p></p>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a href="<?= $prevLink ?>" class="btn btn-outline-secondary">
                                                        <i class="ti-angle-left"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-secondary">
                                                        <i class="ti"><?= $page ?></i>
                                                    </button>
                                                    <a href="<?= $nextLink ?>" class="btn btn-outline-secondary">
                                                        <i class="ti-angle-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="col-lg-<?= $order["status"] === 'New' ? '5' : '7' ?> grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h4 class="card-title mb-0">Order Items</h4>
                                                    <p class="card-description mb-0">
                                                        Summary of products in this order
                                                    </p> 
                                                </div>
                                                <?php if( count ( $orderItems ) > 0 && count( $orderPayments ) === 0 ) { ?>
                                                <a href="orders_edit_order.php?reference=<?= $order_id ?>" class="btn btn-outline-primary btn-icon-text">
                                                    <i class="ti-pencil btn-icon-prepend"></i>
                                                    Edit Order
                                                </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="pl-0">Product</th>
                                                        <th class="text-center">Quantity</th>
                                                        <th class="text-center">Unit</th>
                                                        <th class="text-center">Amount</th>
                                                        <?php if ($order["status"] === 'New') { ?>
                                                        <th class="text-center">
                                                            <i class="ti-settings"></i>
                                                        </th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $total = 0;
                                                    foreach ($orderItems as $orderItem){ 
                                                        $total += (float) $orderItem['amount'];
                                                        $itemProduct = $product->find( $orderItem["item"] );
                                                    ?>
                                                        <tr>
                                                            <td class="pl-0"><?= $itemProduct["description"] ?> - <?= $orderItem["size"] ?></td>
                                                            <td class="text-center"><?= $orderItem["quantity"] ?></td>
                                                            <td class="text-center"><?= money_format( "&#8358; %!n", $orderItem["price"] ) ?></td>
                                                            <td class="text-center"><?= money_format( "&#8358; %!n", $orderItem["amount"] ) ?></td>
                                                            <?php if ($order["status"] === 'New') { ?>
                                                            <td class="text-center">
                                                                <form method="post" action="orders_action.php">
                                                                    <input type="hidden" name="order" value="<?= $order_id ?>"/>
                                                                    <input type="hidden" name="item" value="<?= $orderItem['id'] ?>"/>
                                                                    <input type="hidden" name="item_type" value="<?= $orderItem['item_type'] ?>"/>
                                                                    <button type="submit" name="delete_order_item" value="delete" class="btn btn-outline-danger btn-rounded btn-icon mr-2">
                                                                        <i class="ti-trash"></i>                          
                                                                    </button>
                                                                </form>
                                                            </td>
                                                            <?php } ?>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <div class="row">
                                            <div class="col-sm-12 d-flex align-items-center justify-content-between">
                                                <strong>Total</strong>
                                                <strong><?= money_format( "&#8358; %!n", $total ) ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($order["status"] === 'Completed') { ?>
                            <div class="col-lg-5 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Payment Received</h4>
                                        <!-- <p class="card-description">
                                            Order 
                                        </p> -->
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="pl-0">Payment Method</th>
                                                        <th class="text-center">Received By</th>
                                                        <th class="text-center">Amount</th>
                                                        <th class="text-center">Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    foreach ($orderPayments as $orderPayment){ 
                                                        $totalPayment += (float) $orderPayment['amount'];
                                                        $user = (new RegisteredUser())->find( $orderPayment["received_by"] );
                                                    ?>
                                                        <tr>
                                                            <td class="pl-0"><?= $orderPayment["method"] ?></td>
                                                            <td class="text-center"><?= $user["fullname"] ?></td>
                                                            <td class="text-center"><?= money_format( "&#8358; %!n", $orderPayment["amount"] ) ?></td>
                                                            <td class="text-center"><?= $orderPayment["created_at"] ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <div class="row">
                                            <div class="col-sm-12 d-flex align-items-center justify-content-between">
                                                <strong>Total</strong>
                                                <strong><?= money_format( "&#8358; %!n", $totalPayment ) ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <?php 
                            $outstanding = $total - $discount - $totalPayment;
                            $balMessage  = "Client account balance can pay full order amount.";
                            if( $account['balance'] < $outstanding ) {
                                $balMessage  = "Client account balance can only pay part of order amount.";
                            }

                            if( $account['balance'] == 0 ) {
                                $balMessage  = "Client account balance is low.";
                            }
                        ?>
                        <?php if ($order["status"] === 'Completed') { ?>
                            <div class="row">
                                <div class="col-lg-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div>
                                                <strong>Discount</strong>
                                                <?php if($outstanding > 0){ ?>
                                                <a href="#" title="Apply Discount" data-toggle="modal" data-target="#discountModal" class="ml-2"><i class="fa fa-plus-circle"></i></a>
                                                <?php } ?>
                                            </div>
                                            <strong><?= money_format( "&#8358; %!n", $order['discount'] ) ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <div class="row">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-<?= $order["status"] === 'New' ? 'between' : 'between' ?>">
                                        <?php //if ($order["status"] === 'Completed') { ?>
                                            <div class="sub-price">
                                                <small>OUTSTANDING</small><br />
                                                <span class="text-inverse"><?= money_format( "&#8358; %!n", $outstanding ) ?></span>
                                            </div>
                                        <?php //} ?>
                                        <?php if ($order["status"] === 'New') { ?>
                                            <form action="orders_action.php" method="post">
                                                <input type="hidden" name="reference" value="<?= $order_id ?>" /> 
                                                <input type="hidden" name="total" value="<?= $total ?>" /> 
                                                <button type="submit" name="finish_order" class="btn btn-primary">Continue</button>
                                            </form>
                                        <?php } ?>

                                        <?php if ($order["status"] === 'Completed') { ?>
                                            <div>
                                                <?php if($outstanding > 0){ ?>
                                                <button data-toggle="modal" data-target="#exampleModal" class="btn btn-md btn-outline-primary mr-2">Accept Payment</button>
                                                <?php } ?>
                                                <a href="orders_summary_order.php?reference=<?= $order_id ?>" class="btn btn-md btn-outline-success">View Invoice</a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Site Content -->
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
                                            <?php if( $account ){ ?>
                                            <div class="row">
                                                <div class="col-md-12 stretch-card grid-margin">
                                                    <div class="card data-icon-card-primary">
                                                        <div class="card-body">
                                                            <p class="card-title text-white">Client Account Balance</p>                      
                                                            <div class="row">
                                                                <div class="col-12 text-white">
                                                                    <h3><?= money_format( "&#8358; %!n", $account["balance"] ) ?></h3>
                                                                    <p class="text-white font-weight-500 mb-0 mt-1">
                                                                        <?= $balMessage ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div class="form-group">
                                                <label for="inputPaidAmount">Paid Amount</label>
                                                <input type="number" min="1" max="<?= $outstanding ?>" name="amount" class="form-control" id="inputPaidAmount" placeholder="Paid Amount" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="exampleSelectMethod">Method of Payment</label>
                                                <select required name="pay_method" class="form-control" id="exampleSelectMethod">
                                                    <option value="">Select Method of Payment</option>
                                                    
                                                    <?php if( $account['balance'] > 0 ) { ?>
                                                        <option value="Account">Client Account</option>
                                                    <?php } ?>
                                                    <?php if( $account['balance'] <= 0 ){ ?>
                                                        <option value="Cash">Cash</option>
                                                        <option value="POS">POS</option>
                                                        <option value="Transfer">Transfer</option>
                                                        <option value="Deposit">Direct Deposit</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="accept_payment" value="submit" class="btn btn-primary">Accept Payment</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Modal -->

                        <!-- Modal -->
                        <div class="modal fade" id="discountModal" tabindex="-1" role="dialog" aria-labelledby="discountModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form method="POST" action="orders_action.php">
                                        <input type="hidden" name="order_id" value="<?= $order_id ?>" />
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="inputPaidAmount">Discount Amount</label>
                                                <input type="number" name="amount" class="form-control" value="<?= $order['discount'] ?>" placeholder="Discount Amount">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="apply_discount" value="submit" class="btn btn-primary">Apply</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Modal -->
                    </div>

                    <?php include "includes/inc-footer-main.php" ?>
                </div>

            </div>
        </div>

        <?php include "includes/inc-footer-tags.php" ?>
    </body>
</html>