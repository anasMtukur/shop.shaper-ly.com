<?php 
include "includes/inc-system-autoload.php";  
include "includes/inc-header-main-session.php";

use SRC\Services\UI\UIFragments;
use SRC\DB\Entities\RegisteredUser;


$user_id = '0';
if( isset( $_REQUEST['reference'] ) ){
    $user_id = $_REQUEST['reference'];
}

$item = ( new RegisteredUser() )->find( $user_id );

if(!$item){
    header( "Location: users_manage.php?info=Item not found" );
    exit();
}

$is_admin   = $item['is_admin'] === '1' ? 'checked' : '';
$is_manager = $item['is_manager'] === '1' ? 'checked' : '';

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
                        <?php UIFragments::renderPageTitle("System Users", "Add New System Admin User!", "Cancel", "href='users_manage.php'") ?>
                        <!-- Site Content -->
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                    
                                        <form class="forms-sample" method="POST" action="users_action.php">
                                            <input type="hidden" name="reference" class="form-control" id="exampleInputId1" value="<?= $item["id"] ?>">
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Full Name</label> 
                                                <input type="text" class="form-control" name="fullname" placeholder="Fullname" value="<?= $item['fullname'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Username</label>
                                                <input type="tel" class="form-control" name="username" placeholder="Username" value="<?= $item['username'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Password</label>
                                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                                                <small class="input-helper">Enter Existing User Password or New Password</small>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Select User Role</label>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" name="role" id="optionsRadios1" value="admin" <?= $is_admin ?> required>
                                                    Admin
                                                    <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" name="role" id="optionsRadios2" value="manager" <?= $is_manager ?> required>
                                                    Manager
                                                    <i class="input-helper"></i></label>
                                                </div>
                                                
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-sm-12 d-flex align-item-center justify-content-end">
                                                    <button type="submit" name="update" class="btn btn-outline-primary mr-2">Submit</button>
                                                    <a href="users_manage.php" class="btn btn-outline-danger">Cancel</a>
                                                </div>
                                            </div>
                                        </form>

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