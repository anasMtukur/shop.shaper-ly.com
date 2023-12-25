<?php 
include "includes/inc-system-autoload.php"; 
include "includes/inc-header-main-session.php";

use SRC\DB\Entities\RegisteredUser;

$errorMessage   = '';
$registeredUser = new RegisteredUser();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if ( isset( $_POST['add'] ) ) {
        $fullname = $_POST['fullname'];
        $username = $_POST['username'];
        $role     = $_POST['role'];
        $password = $_POST['password'];

        $is_admin   = $role === 'admin';
        $is_manager = $role === 'manager';

        $account = $registeredUser->findBy( 'username', $username );
        if ( !$account ) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $created = $registeredUser->create( [
                'fullname'   => $fullname,
                'username'   => $username,
                'password'   => $hashed_password,
                'is_admin'   => $is_admin,
                'is_manager' => $is_manager,
            ]);
    
            if( $created ) {
                header( "Location: users_manage.php?&success=New user created successfully" );
                exit();
            }
        }
			
        $errorMessage = "There was a problem with your submission, please try again.";

    } else if ( isset( $_POST['update'] ) ) {
        $reference = $_POST['reference'];
        $fullname  = $_POST['fullname'];
        $username  = $_POST['username'];
        $role      = $_POST['role'];
        $password  = $_POST['password'];

        $is_admin   = $role === 'admin';
        $is_manager = $role === 'manager';
        
        $account = $registeredUser->find( $reference );
        if ( $account ) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $updated = $registeredUser->update( $reference, [
                'fullname'   => $fullname,
                'username'   => $username,
                'password'   => $hashed_password,
                'is_admin'   => $is_admin,
                'is_manager' => $is_manager,
            ]);
    
            if( $updated ) {
                header( "Location: users_manage.php?&success=New client added successfully" );
		        exit();
            }
        }	
			
        $errorMessage = "There was a problem with your submission, please try again.";
    } else if ( isset( $_POST['block'] ) ) {
        $reference   = $_POST['reference'];
        
        $account = $registeredUser->find( $reference );
        if ( $account ) {
            $updated = $registeredUser->update( $reference, [
                'is_blocked' => '1',
            ]);
    
            if( $updated ) {
                header( "Location: users_manage.php?&success=User blocked succesfully." );
		        exit();
            }
        }	
			
        $errorMessage = "There was a problem with your submission, please try again.";
    }

}

header( "Location: users_manage.php?info=" . $errorMessage );
exit();

?>