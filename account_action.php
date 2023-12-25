<?php 
include "includes/inc-system-autoload.php"; 
include "includes/inc-header-main-session.php";

use SRC\AUTH\UserAuth;
use SRC\DB\Entities\RegisteredUser;

$errorMessage = '';
$regUser      = new RegisteredUser();
$auth         = new UserAuth();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if ( isset( $_POST['update'] ) ) {
        $reference = $logged_account['id'];
        $fullname  = $_POST['fullname'];
        $username  = $_POST['username'];
        $password  = $_POST['password'];

        $account = $regUser->find( $reference );
        if ( $account ) {
            $hashed_password =  $account["password"];
            if(password_verify($password, $hashed_password)) {
                $updated = $regUser->update( $reference, [
                    'fullname' => $fullname,
                    'username' => $username,
                ]);
        
                if( $updated ) {
                    $auth->doLogin( $username, $password );
                    header( "Location: account.php?&success=Profile Details updated successfully" );
                    exit();
                }
            }
        }
			
        $errorMessage = "There was a problem with your submission, please try again.";

    } else if ( isset( $_POST['change_password'] ) ) {
        $reference   = $logged_account['id'];
        $newPassword = $_POST['npassword'];
        $password    = $_POST['password'];
        
        
        $hashed_new_password = password_hash($newPassword, PASSWORD_BCRYPT);
        $account = $regUser->find( $reference );
        if ( $account ) {
            $hashed_password = $account["password"];
            if(password_verify($password, $hashed_password)) {
                $updated = $regUser->update( $reference, [
                    'password' => $hashed_new_password,
                ]);
        
                if( $updated ) {
                    header( "Location: logout.php" );
                    exit();
                }
            }
        }	
			
        $errorMessage = "There was a problem with your submission, please try again.";
    }
}

header( "Location: account.php?info=" . $errorMessage );
exit();

?>