<?php 
include "includes/inc-system-autoload.php"; 
include "includes/inc-header-main-session.php";

use SRC\Services\UI\UIFragments;
use SRC\DB\Entities\Client;
use SRC\DB\Entities\ClientAccount;
use SRC\DB\Entities\ClientAccountTransaction;

$client_id = '0';
if( isset( $_REQUEST['reference'] ) ){
    $client_id = $_REQUEST['reference'];
}

$client = ( new Client() )->find( $client_id );

if(!$client){
    header( "Location: clients_manage.php?info=Item not found" );
    exit();
}

$account = ( new ClientAccount() )->findBy( 'client', $client_id );
if(!$account){
    header( "Location: clients_manage.php?info=Account not found" );
    exit();
}
$accountId = $account['id'];
$matches    = [
    'client' => [
        'link'  => 'AND',
        'value' => $client_id,
    ],
    'account' => [
        'link'  => 'AND',
        'value' => $accountId,
    ],
];
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

if( isset( $_SESSION["sp_transactions_pages"] ) ) {
	$pages = $_SESSION["sp_transactions_pages"];
	if( !array_key_exists( $page, $pages ) ) {
		$pages[$page] = [
			'id'   => $offsetId,
  			'date' => $offsetDate,
		];
	}
	$_SESSION["sp_transactions_pages"] = $pages;
}else{
	$_SESSION["sp_transactions_pages"] = [
		$page => [
			'id'   => $offsetId,
  			'date' => $offsetDate,
		],
	];
}

$transx       = new ClientAccountTransaction();
$transactions = $transx->getPagedList( $size, $offsetId, $offsetDate, true, $matches ); 
$next         = $transactions["offset"];
$checkPages   = round( ( (int)$transactions["count"] / $size ) );
$totalPages   = $checkPages >= 1 ? $checkPages : 1;

$pages = $_SESSION["sp_transactions_pages"];
if( $page > 1 ) {
	$prev  = $pages[ $page - 1 ];
	$prevLink = '?reference=' . $client_id . '&current_page=' . $page . '&move=prev&offset_id=' .$prev["id"]. '&offset_date=' . $prev["date"];
}

$nextAllowed = (($page) * $size) - (int)$transactions['count'];
if( $nextAllowed < 0) {
	$nextLink = '?reference=' . $client_id . '&current_page=' . $page . '&move=next&offset_id=' .$next["id"]. '&offset_date=' . $next["date"];
}

$balance = $account['balance'];
$currency_unit = $balance < 0 ? " - &#8358;" : "&#8358;";
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
                        <?php //UIFragments::renderPageTitle("Clients", "Add New Client!", "Cancel", "href='clients_manage.php'") ?>
                        <!-- Site Content -->
                        <div class="row">
                            <div class="col-md-8 grid-margin stretch-card">
                                <div class="card tale-bg">
                                    <div class="card-people mt-auto" style="color: white; border-radius:15px; min-height: 200px; max-height: 200px; background-image: url(images/card-bg.png);">
                                        <div class="d-flex flex-column justify-content-between px-3">
                                            <div class="d-flex justify-content-between" style="height: 130px;">
                                                <div>
                                                    <h2 class="mb-0 font-weight-normal">
                                                        <i class="ti-wallet mr-2"></i>
                                                    </h2>
                                                </div>
                                                <div class="ml-2">
                                                    <h2 class="mb-0 font-weight-normal text-<?= $balance < 0 ? "danger" : "white" ?>">
                                                        <?= money_format( $currency_unit . " %!n", $account['balance'] ) ?>
                                                    </h2>
                                                </div>
                                            </div>

                                            <div>
                                                <h2 class="font-weight-normal">
                                                    <?= $client['name'] ?>
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button data-toggle="modal" data-target="#creditModal" class="btn btn-social-icon btn-outline-success" style="height: 160px; width: 100%;">
                                                    <div class="d-flex flex-column align-items-center justify-content-center" style="height: 100%; width: 100%;">
                                                        <i class="fa fa-plus fa-3x"></i>
                                                        <h5 class="mt-8">Credit</h5>
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="col-md-6">
                                                <button data-toggle="modal" data-target="#debitModal" type="button" class="btn btn-social-icon btn-outline-danger" style="height: 160px; width: 100%;">
                                                    <div class="d-flex flex-column align-items-center justify-content-center" style="height: 100%; width: 100%;">
                                                        <i class="fa fa-minus fa-2x"></i>
                                                        <h5 class="mt-8">Debit</h5>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Transaction</th>
                                                        <th>Method</th>
                                                        <th class="text-center">Amount</th>
                                                        <th class="text-center">Date</th>
                                                        <th class="text-right">
                                                            <i class="ti-settingsx"></i>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?= $transactions['rows'] ?>
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

                        <!-- Modal -->
                        <div class="modal fade" id="creditModal" tabindex="-1" role="dialog" aria-labelledby="creditModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="creditModal">Credit Transaction</h5>
                                        
                                    </div>

                                    <form method="POST" action="clients_account_action.php">
                                        <input type="hidden" name="client_id" value="<?= $client_id ?>" />
                                        <input type="hidden" name="account_id" value="<?= $account['id'] ?>" />
                                        <input type="hidden" name="transaction" value="credit" />
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
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="account_transaction" value="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Modal -->

                        <!-- Modal -->
                        <div class="modal fade" id="debitModal" tabindex="-1" role="dialog" aria-labelledby="debitModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="debitModal">Debit Transaction</h5>
                                        
                                    </div>

                                    <form method="POST" action="clients_account_action.php">
                                        <input type="hidden" name="client_id" value="<?= $client_id ?>" />
                                        <input type="hidden" name="account_id" value="<?= $account['id'] ?>" />
                                        <input type="hidden" name="transaction" value="debit" />
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
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="account_transaction" value="submit" class="btn btn-primary">Submit</button>
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