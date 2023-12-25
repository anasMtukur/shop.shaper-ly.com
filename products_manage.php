<?php 
include "includes/inc-system-autoload.php";  
include "includes/inc-header-main-session.php";

use SRC\Services\UI\UIFragments;
use SRC\DB\Entities\Product;

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

if( isset( $_SESSION["wbs_shop_pages"] ) ) {
	$pages = $_SESSION["wbs_shop_pages"];
	if( !array_key_exists( $page, $pages ) ) {
		$pages[$page] = [
			'id'   => $offsetId,
  			'date' => $offsetDate,
		];
	}
	$_SESSION["wbs_shop_pages"] = $pages;
}else{
	$_SESSION["wbs_shop_pages"] = [
		$page => [
			'id'   => $offsetId,
  			'date' => $offsetDate,
		],
	];
}

$product    = new Product();
$products   = $product->getProductList( $size, $offsetId, $offsetDate, true, $matches ); 
$next       = $products["offset"];
$checkPages = round( ( (int)$products["count"] / $size ) );
$totalPages = $checkPages >= 1 ? $checkPages : 1;

$pages = $_SESSION["wbs_shop_pages"];
if( $page > 1 ) {
	$prev  = $pages[ $page - 1 ];
	$prevLink = '?current_page=' . $page . '&move=prev&offset_id=' .$prev["id"]. '&offset_date=' . $prev["date"];
}

$nextAllowed = (($page) * $size) - (int)$products['count'];
if( $nextAllowed < 0) {
	$nextLink = '?current_page=' . $page . '&move=next&offset_id=' .$next["id"]. '&offset_date=' . $next["date"];
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
                        <?php UIFragments::renderPageTitle("Products", "Manage all product listing!", "New Product", "href='products_new.php'") ?>
                        <!-- Site Content -->
                        <div class="row">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Product Description</th>
                                                        <th class="text-right">
                                                            <i class="ti-settingsx"></i>
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