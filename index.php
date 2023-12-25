<?php
include "includes/inc-system-autoload.php"; 

use SRC\AUTH\UserAuth;

$auth = new UserAuth();
$errorMessage = 'Sign in to continue.';

if( isset( $_POST["login" ] ) ){
	$username  = $_POST["username" ];
	$password  = $_POST["password"];

	$userId = $auth->doLogin( $username, $password );
	if ( $userId ) {
		header("Location: dashboard.php");
	}else{
		$errorMessage = "Login failed. Please try again.";
	}
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php include "includes/inc-header-main-tags.php" ?>

    <body>
        <div class="container-scroller">
            <div class="container-fluid page-body-wrapper full-page-wrapper" style="max-height: 90vh; min-height: 90vh;">
                <div class="content-wrapper d-flex align-items-center auth px-0">
                    <div class="row w-100 mx-0">
                        <div class="col-lg-4 mx-auto">
                            
                            <div class="auth-form-light text-center py-5 px-4 px-sm-5">
                                <div class="brand-logo">
                                    <img src="images/logo.png" style="width: 100px;" alt="logo">
                                </div>
                                <h4>Shaper-ly Order Management System</h4>
                                <h6 class="font-weight-light">
                                    <?= $errorMessage ?>
                                </h6>

                                <form class="pt-3" action="#" method="POST">
                                    <div class="form-group">
                                        <input type="text" name="username" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Username">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password">
                                    </div>
                                    <!--<div class="my-2 d-flex justify-content-between align-items-center">
                                        <div class="form-check">
                                            <label class="form-check-label text-muted">
                                            <input type="checkbox" class="form-check-input">
                                            Keep me signed in
                                            </label>
                                        </div>
                                    </div>-->
                                    <div class="mt-3">
                                        <button type="submit" name="login" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                                    </div>
                                    <!--<div class="my-2 d-flex justify-content-end align-items-center">
                                        <a href="#" class="auth-link text-black">Forgot password?</a>
                                    </div>-->
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
            </div>
            <!-- page-body-wrapper ends -->
            <?php include "includes/inc-footer-main.php" ?>
        </div>
        
    </body>
    <?php include "includes/inc-footer-tags.php" ?>
</html>