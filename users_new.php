<?php 
include "includes/inc-system-autoload.php";  
include "includes/inc-header-main-session.php";

use SRC\Services\UI\UIFragments;
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
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Full Name</label> 
                                                <input type="text" class="form-control" name="fullname" placeholder="Fullname" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Username</label>
                                                <input type="tel" class="form-control" name="username" placeholder="Username" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Password</label>
                                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Select User Role</label>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" name="role" id="optionsRadios1" value="admin" required>
                                                    Admin
                                                    <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" name="role" id="optionsRadios2" value="manager" required>
                                                    Manager
                                                    <i class="input-helper"></i></label>
                                                </div>
                                                
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-sm-12 d-flex align-item-center justify-content-end">
                                                    <button type="submit" name="add" class="btn btn-outline-primary mr-2">Submit</button>
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