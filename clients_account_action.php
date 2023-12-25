<?php 
include "includes/inc-system-autoload.php";  
include "includes/inc-header-main-session.php";

use SRC\DB\Entities\ClientAccountTransaction;
use SRC\Services\Account\AccountHelper;

$errorMessage = '';
$client_id    = 0;
$account_id   = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ( isset( $_POST['account_transaction'] ) ) {
        $user_id     = $logged_account['id'];
        $client_id   = $_POST['client_id'];
        $account_id  = $_POST['account_id'];
        $transaction = $_POST['transaction'];
        $amount      = $_POST['amount'];
        $method      = $_POST['pay_method'];

        $created = (new ClientAccountTransaction())->create([
			'client'      => $client_id,
			'account'     => $account_id,
			'created_by'  => $user_id,
			'transaction' => $transaction,
			'method'      => $method,
			'amount'      => $amount,
		]);

        if( $created ) {
            (new AccountHelper())->updateAccountBalance( $account_id, $transaction, $amount);
			header( "Location: clients_account.php?reference=" . $client_id  . "&success=New payment record added successfully" );
			exit();
		}
		
		$errorMessage = "There was a problem with your submission, please try again.";
    }

	if ( isset( $_POST['delete'] ) ) {
		$cat       = new ClientAccountTransaction();
		$reference = $_POST['reference'];
        $item      = $cat->find( $reference );

        $transaction = $item['transaction'] ===  'credit' ? 'debit' : 'credit';
        $amount      = $item['amount'];
        $account_id  = $item['account'];
        $client_id   = $item['client'];
        
		$deleted = $cat->delete( $reference );		
		if( $deleted ) {
            (new AccountHelper())->updateAccountBalance( $account_id, $transaction, $amount );

			header( "Location: clients_account.php?reference=" . $client_id . "&success=Product added to order successfully" );
			exit();
		}
		
		$errorMessage = "There was a problem with your submission, please try again.";
	}
}

header( "Location: clients_account.php?reference=" . $client_id . "&info=" . $errorMessage );
exit();
?>