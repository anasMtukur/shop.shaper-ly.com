<?php 
include "includes/inc-system-autoload.php"; 
include "includes/inc-header-main-session.php";

use SRC\DB\Entities\Client;
use SRC\DB\Entities\ClientAccount;

$errorMessage     = '';
$client = new Client();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//var_dump( $_POST );
	if ( isset( $_POST['add_client'] ) ) {
        $name     = $_POST['client_name'];
        $mobile   = empty( trim( $_POST['mobile'] ) ) ? "N/A" : $_POST['mobile'];
        $email    = empty( trim( $_POST['email'] ) ) ? "N/A" : $_POST['email'];
        $add1     = "";//$_POST['add1'];
        $add2     = "";//$_POST['add2'];
        $postcode = "";//$_POST['postcode'];
        $city     = "";//$_POST['city'];
        $state    = $_POST['state'];

        $add      = [
            "address_line1" => $add1,
            "address_line2" => $add2,
            "postcode"      => $postcode,
            "city"          => $city,
            "state"         => $state,
        ];

        $addJson = json_encode($add);

        $found   = $client->list("WHERE is_deleted='0' AND (name='" .$name. "' OR email='" .$email. "' OR mobile='" .$mobile. "')");
        if( count ( $found ) > 0 ) {
            header( "Location: clients_manage.php?&error=Another client with the same Name, Email or Phone number already exist." );
			exit();
        }

        $created = (new Client())->create([
			'name'    => $name,
			'email'   => $email,
			'mobile'  => $mobile,
			'address' => $addJson,
		]);
        
		if( $created ) {
            (new ClientAccount())->create([
                'client' => $created,
            ]);
			header( "Location: clients_edit.php?reference=" . $created . "&success=New client added successfully" );
			exit();
		}	
			
        $errorMessage = "There was a problem with your submission, please try again.";

    } else if ( isset( $_POST['update_client'] ) ) {
        $id       = $_POST['reference'];
        $name     = $_POST['client_name'];
        $mobile   = $_POST['mobile'];
        $email    = $_POST['email'];
        $add1     = "";//$_POST['add1'];
        $add2     = "";//$_POST['add2'];
        $postcode = "";//$_POST['postcode'];
        $city     = "";//$_POST['city'];
        $state    = $_POST['state'];
        
        $add      = [
            "address_line1" => $add1,
            "address_line2" => $add2,
            "postcode"      => $postcode,
            "city"          => $city,
            "state"         => $state,
        ];

        $addJson = json_encode($add);

        $found   = $client->list("WHERE is_deleted='0' AND (name='" .$name. "' OR email='" .$email. "' OR mobile='" .$mobile. "')");
        if( count ( $found ) > 0 ) {
            header( "Location: clients_edit.php?reference=" . $id . "&error=Another client with the same Name, Email or Phone number already exist." );
			exit();
        }

        $updated = (new Client())->update( $id, [
			'name'    => $name,
			'email'   => $email,
			'mobile'  => $mobile,
			'address' => $addJson,
		] );
        
		if( $updated ) {
			header( "Location: clients_edit.php?reference=" . $id . "&success=Client updated successfully" );
			exit();
		}	
			
        $errorMessage = "There was a problem with your submission, please try again.";
    } else if ( isset( $_POST['delete'] ) ) {
        $id = $_POST['reference'];

		$updates = [
			'is_deleted' => '1',
		];

		$deleted = (new Client())->update( $id, $updates );
		if( $deleted ) {
			header( "Location: clients_manage.php?success=Client deleted successfully" );
			exit();
		}

		$errorMessage = "Failed to delete client. Please try again.";
    }
}

header( "Location: clients_manage.php?info=" . $errorMessage );
exit();

?>