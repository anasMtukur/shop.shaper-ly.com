<?php
namespace SRC\AUTH;

use SRC\DB\Entities\RegisteredUser;

/** User Authentication Class  */
class UserAuth {

    function doLogin( $user_name, $user_password ){
        $regUser = new RegisteredUser();
        
        $account = $regUser->findBy( 'username', $user_name );
        if ( $account ){
            if( $account['is_blocked'] === "1" ){
                return 0;
            }

            $hashed_password =  $account["password"];
            if(password_verify($user_password, $hashed_password)){
                $_SESSION["sps_logged_user_id"]      = $account["id"];
                $_SESSION["sps_logged_user_account"] = $account;                

                $t=time();
                $td = date( "Y-m-d HH:mm:ss", $t );
                $_SESSION["sps_logged"] = $td;
                
                return $account["id"];
            }
        }   
        return 0;
    }
}
?>