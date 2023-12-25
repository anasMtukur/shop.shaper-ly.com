<?php 
include "includes/inc-system-autoload.php";  
include "includes/inc-header-main-session.php";

use SRC\Services\UI\UIFragments;
use SRC\DB\Entities\Order;
use SRC\DB\Entities\Product;
use SRC\DB\Entities\OrderItem;

$order_id = '0';
if( isset( $_REQUEST['reference'] ) ){
    $order_id = $_REQUEST['reference'];
}

$order = ( new Order() )->find( $order_id );
if(!$order){
    header( "Location: orders_manage.php?info=Item not found" );
    exit();
}

$orderItems = (new OrderItem())->list([
    'order_id' => [
        'link'  => 'AND',
        'value' => $order_id
    ],
]);

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
                        <?php UIFragments::renderPageTitle( 'Edit Order', $order["order_no"] . '<label class="badge badge-warning ml-2">' . $order["status"] . '</label>', "Close", "href='orders_manage_order.php?reference=" . $order_id . "'") ?>
                        <!-- Site Content -->
                        <div class="row">
                            <div class="col-lg-4 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body" style="min-height: 60vh;">
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
                            <div class="col-lg-8 grid-margin stretch-card">
                                <form class="w-100" method="post" action="orders_action.php">
                                    <input name="order" class="form-control" type="hidden" value="<?= $order_id ?>" />
                                    <div class="card">
                                        <div class="card-body"  style="min-height: 60vh;">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th width="4%">SNo.</th>
                                                            <th>Product</th>
                                                            <th>Select Size</th>
                                                            <th width="25%">Quantity</th>
                                                            <th width="5%"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        foreach ($orderItems as $key => $orderItem){ 
                                                            $oid         = $orderItem["id"];
                                                            $itemProduct = $product->find( $orderItem["item"] );
                                                            $sizes       = json_decode( $itemProduct['sizes_available'] );
                                                        ?>
                                                            <tr>
                                                                <td>
                                                                    <?= $key + 1 ?>
                                                                    <input name="oid[]" class="form-control" type="hidden" value="<?= $oid ?>" readonly />
                                                                </td>
                                                                <td><?= $itemProduct["description"] ?></td>
                                                                <td>
                                                                    <div class="form-group mt-2">
                                                                        <select name="oi_<?= $oid ?>_size" class="form-control" required>
                                                                            <option value="">Select Product Size</option>
                                                                            <?php 
                                                                                foreach ($sizes->sizes as $key => $size) {
                                                                                    $value    = $size->title . ':' . $size->price;
                                                                                    $selected = $size->title === $orderItem["size"] ? 'selected' : '';
                                                                                    $html     = '<option value="' .$value. '" ' .$selected. '>';
                                                                                    $html    .= $size->title . ':' . money_format( "&#8358; %!n", $size->price );
                                                                                    $html    .= '</option>';

                                                                                    echo $html;
                                                                                }
                                                                                unset( $size );
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="form-group mt-2">
                                                                        <input name="oi_<?= $oid ?>_quantity" class="form-control" type="number" value="<?= $orderItem["quantity"] ?>" required />
                                                                    </div>
                                                                </td>
                                                                <td class="text-center">
                                                                    <form method="post" action="orders_action.php">
                                                                        <input type="hidden" name="order" value="<?= $order_id ?>"/>
                                                                        <input type="hidden" name="item" value="<?= $orderItem['id'] ?>"/>
                                                                        <input type="hidden" name="item_type" value="<?= $orderItem['item_type'] ?>"/>
                                                                        <button type="submit" name="delete_order_item" value="delete" class="btn btn-outline-danger btn-rounded btn-icon  mt-0">
                                                                            <i class="ti-trash"></i>                          
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-white">
                                            <div class="row">
                                                <div class="col-sm-12 d-flex align-items-center justify-content-end">
                                                    <button type="submit" name="update_order" value="submit"  class="btn btn-outline-primary btn-icon-text">
                                                        Update Order
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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