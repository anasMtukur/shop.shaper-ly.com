<?php 
include "includes/inc-system-autoload.php"; 
include "includes/inc-header-main-session.php";

use SRC\DB\Entities\Expense;

$errorMessage     = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//var_dump( $_POST );
	if ( isset( $_POST['add_expense'] ) ) {
        $user_id     = $logged_account['id'];
        $description = $_POST['description'];
        $method      = $_POST['method'];
        $amount      = $_POST['amount'];

        $created = (new Expense())->create([
			'description' => $description,
			'method'      => $method,
			'amount'      => $amount,
            'created_by'  => $user_id,
		]);
        
		if( $created ) {
			header( "Location: expenses_manage.php?&success=New expense added successfully" );
			exit();
		}	
			
        $errorMessage = "There was a problem with your submission, please try again.";

    } else if ( isset( $_POST['update_expense'] ) ) {
        $id          = $_POST['reference'];
        $description = $_POST['description'];
        $method      = $_POST['method'];
        $amount      = $_POST['amount'];

        $updated = (new Expense())->update( $id, [
            'description' => $description,
			'method'      => $method,
			'amount'      => $amount,
		] );
        
		if( $updated ) {
			header( "Location: expenses_edit.php?reference=" . $created . "&success=Expense updated successfully" );
			exit();
		}	
			
        $errorMessage = "There was a problem with your submission, please try again.";
    } else if ( isset( $_POST['delete'] ) ) {
        $id = $_POST['reference'];

		$deleted = (new Expense())->delete( $id );
		if( $deleted ) {
			header( "Location: expenses_manage.php?success=Client deleted successfully" );
			exit();
		}

		$errorMessage = "Failed to delete expense. Please try again.";
    }
}

header( "Location: expenses_manage.php?info=" . $errorMessage );
exit();

?>