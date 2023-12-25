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
                        <?php UIFragments::renderPageTitle("Clients", "Add New Client!", "Cancel", "href='clients_manage.php'") ?>
                        <!-- Site Content -->
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                    
                                        <form class="forms-sample" method="POST" action="clients_action.php">
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Client Name</label> 
                                                <input type="text" class="form-control" name="client_name" placeholder="Client Name" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Mobile Number</label>
                                                <input type="tel" class="form-control" name="mobile" placeholder="Mobile No." required>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Email address</label>
                                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                                            </div>

                                            <!--<div class="form-group">
                                                <label for="exampleInputEmail1">Address Line 1</label>
                                                <input type="text" class="form-control" name="add1" placeholder="Address Line 1" >
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Address Line 2</label>
                                                <input type="text" class="form-control" name="add2" placeholder="Address Line 2">
                                            </div>-->
                                            <div class="row">
                                                <!--<div class="col-sm-4 form-group">
                                                    <label for="exampleInputEmail1">Postal Code</label>
                                                    <input type="text" class="form-control" name="postcode" placeholder="Postal Code" >
                                                </div>
                                                <div class="col-sm-4 form-group">
                                                    <label for="exampleInputEmail1">City</label>
                                                    <input type="text" class="form-control" name="city" placeholder="City" >
                                                </div>-->
                                                <div class="col-sm-12 form-group">
                                                    <label for="exampleInputEmail1">State</label>
                                                    <input type="text" class="form-control" name="state" placeholder="State" required>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-sm-12 d-flex align-item-center justify-content-end">
                                                    <button type="submit" name="add_client" class="btn btn-outline-primary mr-2">Submit</button>
                                                    <a href="clients_manage.php" class="btn btn-outline-danger">Cancel</a>
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