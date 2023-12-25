<?php 
include "includes/inc-system-autoload.php"; 
include "includes/inc-header-main-session.php";

$account = $logged_account;
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
                        <?php include "includes/dashboard/inc-admin-dashboard-title.php" ?>
                        <!-- Site Content -->
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Update User Details</h4>
                                        <form class="forms-sample" action="account_action.php" method="post">
                                            <div class="form-group">
                                                <label for="exampleInputFullname1">Account Name</label>
                                                <input type="text" class="form-control" id="exampleInputFullname1" placeholder="Your Full Name" name="fullname" value="<?= $account["fullname"] ?>" required />
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Username</label>
                                                <input type="text" class="form-control" id="exampleInputUsername1" placeholder="Username" name="username" value="<?= $account["username"] ?>" required />
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Your Password</label>
                                                <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password" required />
                                                <small id="passwordHelp" class="form-text text-muted">Enter your password to proceed.</small>
                                            </div>

                                            <div class="form-group d-flex justify-content-end">
                                                <button type="submit" name="update" class="btn btn-primary mr-2">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title text-danger">Change Password</h4>
                                        <form class="forms-sample" action="account_action.php" method="post">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">New Password</label>
                                                <input type="password" name="npassword" class="form-control" id="exampleInputPassword1" placeholder="New Password" required />
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputConfirmPassword1">Confirm New Password</label>
                                                <input type="password" name="cpassword" class="form-control" id="exampleInputConfirmPassword1" placeholder="Confirm Password" required />
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Your Password</label>
                                                <input type="password" class="form-control" id="inputPassword1" name="password" placeholder="Your Password" required />
                                                <small id="passwordHelp" class="form-text text-muted">Enter your password to proceed.</small>
                                            </div>
                                            <div class="form-group d-flex justify-content-end">
                                                <button type="submit" name="change_password" class="btn btn-outline-danger mr-2">Submit</button>
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