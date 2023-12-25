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
                        <?php UIFragments::renderPageTitle("Products", "Add new product.", "Cancel", "href='products_manage.php'") ?>
                        <!-- Site Content -->
                        <div class="row">
                            <div class="col-12">
                                <form method="POST" action="products_action.php" class="forms-sample grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="exampleInputName1">Product Description</label>
                                                <input type="text" name="description" class="form-control" id="exampleInputName1" placeholder="Name" required>
                                            </div>
                                            
                                            <h4 class="card-title">Sizes Available</h4>
                                            <p class="card-description">
                                                Add Available Sizes and their prices
                                            </p>

                                            <div class="row">
                                                <div class="form-group col-sm-5">
                                                    <label for="exampleInputEmail3">Size</label>
                                                    <input name="sizes_available[]" type="text" class="form-control" id="exampleInputEmail3" placeholder="Size">
                                                </div>
                                                <div class="form-group col-sm-5">
                                                    <label for="exampleInputPassword4">Price</label>
                                                    <input name="sizes_available_price[]" type="number" class="form-control" id="exampleInputPassword4" placeholder="Price">
                                                </div>
                                                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                                                    <button id="addEntry" type="button" class="btn btn-outline-secondary btn-rounded btn-icon">
                                                        <i class="ti-plus text-info"></i>                          
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row" id="moreSizeEntries"></div>                                                
                                            
                                        </div>

                                        <div class="card-footer bg-white d-flex justify-content-end" style="border-bottom-left-radius: 20%; border-bottom-right-radius: 20%; z-index: 9999;">
                                            <button type="submit" name="add_product" class="btn btn-primary mr-2">Submit</button>
                                            <a href="products_manage.php" class="btn btn-outline-danger">Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </div>                            
                        </div>
                        <!-- /Site Content -->
                    </div>

                    <?php include "includes/inc-footer-main.php" ?>
                </div>

            </div>
        </div>

        <?php include "includes/inc-footer-tags.php" ?>
        <script>
            const sizeEntryButton = document.getElementById('addEntry');
            
            sizeEntryButton.addEventListener('click', (event) => {
                //function addSizeEntry() {
                    var current = document.getElementById('moreSizeEntries').innerHTML;
                    current = current + '<div class="form-group col-sm-5">';
                    current = current + '<label for="exampleInputEmail3">Size</label>';
                    current = current + '<input name="sizes_available[]" type="text" class="form-control" id="exampleInputEmail3" placeholder="Size">';
                    current = current + '</div>';
                    current = current + '<div class="form-group col-sm-5">';
                    current = current + '<label for="exampleInputPassword4">Price</label>';
                    current = current + '<input name="sizes_available_price[]" type="number" class="form-control" id="exampleInputPassword4" placeholder="Prize">';
                    current = current + '</div>';
                    
                    document.getElementById('moreSizeEntries').innerHTML = current;
                //}
            });
        </script>
    </body>
</html>