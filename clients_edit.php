<?php 
include "includes/inc-system-autoload.php"; 
include "includes/inc-header-main-session.php";

use SRC\Services\UI\UIFragments;
use SRC\DB\Entities\Client;

$client_id = '0';
if( isset( $_REQUEST['reference'] ) ){
    $client_id = $_REQUEST['reference'];
}

$item = ( new Client() )->find( $client_id );

if(!$item){
    header( "Location: clients_manage.php?info=Item not found" );
    exit();
}

$address = json_decode( $item['address'] );
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
                                            <input type="hidden" name="reference" class="form-control" id="exampleInputId1" value="<?= $item["id"] ?>">
                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Client Name</label> 
                                                <input type="text" class="form-control" name="client_name" value="<?= $item["name"] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Mobile Number</label>
                                                <input type="tel" class="form-control" name="mobile" value="<?= $item["mobile"] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Email address</label>
                                                <input type="email" class="form-control" name="email" value="<?= $item["email"] ?>" required>
                                            </div>

                                            <!--<div class="form-group">
                                                <label for="exampleInputEmail1">Address Line 1</label>
                                                <input type="text" class="form-control" name="add1" value="<?= $address->address_line1 ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Address Line 2</label>
                                                <input type="text" class="form-control" name="add2" value="<?= $address->address_line2 ?>">
                                            </div>-->
                                            <div class="row">
                                                <!--<div class="col-sm-4 form-group">
                                                    <label for="exampleInputEmail1">Postal Code</label>
                                                    <input type="text" class="form-control" name="postcode" value="<?= $address->postcode ?>" required>
                                                </div>
                                                <div class="col-sm-4 form-group">
                                                    <label for="exampleInputEmail1">City</label>
                                                    <input type="text" class="form-control" name="city" value="<?= $address->city ?>" required>
                                                </div>-->
                                                <div class="col-sm-12 form-group">
                                                    <label for="exampleInputEmail1">State</label>
                                                    <input type="text" class="form-control" name="state" value="<?= $address->state ?>" required>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-sm-12 d-flex align-item-center justify-content-end">
                                                    <button type="submit" name="update_client" class="btn btn-outline-primary mr-2">Submit</button>
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