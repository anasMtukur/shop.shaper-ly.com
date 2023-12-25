<?php
namespace SRC\Helpers;

class LoggedAccount {

    public static function role(){
        $logged_role = "none";

        if( isset( $_SESSION['sps_logged_user_account'] ) ) {
            $logged_account = $_SESSION['sps_logged_user_account'];
            $logged_role    = $logged_account['is_admin'] === '1' ? 'admin' : 'manager';
        }

		return $logged_role;
	}
}