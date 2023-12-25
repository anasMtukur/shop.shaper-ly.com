<?php 
include "includes/inc-system-autoload.php";  
include "includes/inc-header-main-session.php";

use SRC\DB\Entities\Product;

$errorMessage     = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//var_dump( $_POST );
	if ( isset( $_POST['add_product'] ) ) {
        $description         = $_POST['description'];
		//$has_addons          = isset( $_POST['has_addons'] ) ? $_POST['has_addons'] : 0;
        $availableSizes      = $_POST['sizes_available'];
        $availableSizesPrice = $_POST['sizes_available_price'];

        /* $addonsEntry = "NULL";
        if( isset( $_POST['has_addons'] ) ) {
            $adddons = $_POST['addons'];
            $addonsEntry = join("|", $adddons);
        } */
        

        $sizes = [];
        foreach ( $availableSizes as $key => $value ) {
            if( !empty( $value ) ) {
                $item = [
                    "title" => $value,
                    "price" => $availableSizesPrice[ $key ],
                ];
                $sizes[ $key ] = $item;
            }
        }
        $json = [
            "sizes" => $sizes,
        ];
        $availableSizesJson = json_encode( $json );
        
        $created = (new Product())->create([
			'description'      => $description,
			'sizes_available'  => $availableSizesJson,
		]);
        
		if( $created ) {
			header( "Location: products_edit.php?reference=" . $created . "&success=New product added successfully" );
			exit();
		} else {	
			$errorMessage = "There was a problem with your submission, please try again.";
		}
    } else if ( isset( $_POST['update_product'] ) ) {

        $productId           = $_POST['reference'];
        $description         = $_POST['description'];
        $availableSizes      = $_POST['sizes_available'];
        $availableSizesPrice = $_POST['sizes_available_price'];

        $sizes = [];
        foreach ( $availableSizes as $key => $value ) {
            if( !empty( $value ) ) {
                $item = [
                    "title" => $value,
                    "price" => $availableSizesPrice[ $key ],
                ];
                $sizes[ $key ] = $item;
            }
        }
        $json = [
            "sizes" => $sizes,
        ];
        $availableSizesJson = json_encode( $json );
        
        $updated = (new Product())->update( $productId, [
			'description'      => $description,
			'sizes_available'  => $availableSizesJson,
		] );
        
		if( $updated > 0 ) {
			header( "Location: products_edit.php?reference=" . $productId . "&success=New product added successfully" );
			exit();
		} else {	
			$errorMessage = "There was a problem with your submission, please try again.";
		}

    } else if ( isset( $_POST['delete'] ) ) {

        $product_id   = $_POST['reference'];

		$updates = [
			'is_deleted' => '1',
		];

		$deleted = (new Product())->update( $product_id, $updates );
		if( $deleted ) {
			header( "Location: products_manage.php?success=Product deleted successfully" );
			exit();
		}

		header( "Location: products_manage.php?error=Failed to delete product. Please try again." );
		exit();

    }
}

header( "Location: products_manage.php?info=" . $errorMessage );
exit();
?>