<?php
namespace SRC\DB\Entities;

use SRC\DB\Entities\PagedEntity;
use SRC\Helpers\LoggedAccount;


class Product extends PagedEntity {
	const TABLE_NAME = 'sp_products';

	function __construct() {
        parent::__construct();
        $this->table_name = self::TABLE_NAME;
    }

    public function getProductList( $size = 50, $offsetId = 0, $offsetDate = '2023-01-01 00:00:01', $isPaged = true,  $matches = [], $listOnly = false, $forOrder = false ) {
		$list  = $this->pagedList( $size, $offsetId, $offsetDate, $isPaged,  $matches );
		$items = $list["items"];
		
		if( $listOnly ) {
			return $list;
		}

        $html = '';
		foreach ( $items as $item ) {
            $sizes = json_decode( $item['sizes_available'] );

            $html .= '<tr>';
            $html .= '<td>'.$item["description"].'</td>';
            $html .= '<td class="text-right"><button class="btn btn-icon btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#collapse'.$item['id'].'" aria-expanded="false" aria-controls="collapseExample">';
            $html .= '<i class="ti-angle-down"></i></button></td>';
            $html .= '</tr>';
            $html .= '<tr class="collapse" id="collapse'.$item['id'].'">';
            $html .= '<td colspan="6" style="padding: 0;">';

            if( !$forOrder ){
                $html .= '<div class="card">';
                $html .= '<div class="card-body">';
                $html .= '<div class="row">';
                $html  .= $this->getAvailableSizesCard( $sizes );
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="card-footer">';
                $html .= '<div class="d-flex align-items-center justify-content-end">';
                $html .= '<a class=" mr-2" href="products_edit.php?reference=' . $item['id'] . '" >';
                $html .= '<button class="btn btn-icon btn-sm btn-outline-facebook"><i class="ti-pencil"></i></button>';
                $html .= '</a>';
                // $html .= '<form action="products_action.php" method="POST">';
                // $html .= '<input type="hidden" name="reference" value="' . $item['id'] . '" />';
                // $html .= '<button type="submit" name="delete" class="btn btn-icon btn-sm btn-outline-youtube"><i class="ti-trash"></i></button>';
                // $html .= '</form>';
                if( LoggedAccount::role() === 'admin' ) {
                    $html .= '<button data-toggle="modal" data-target="#deleteModal" data-ref="' . $item['id'] . '" data-conf="Are you sure you want to delete this product? This action iss not reversible." data-action="products_action.php" class="btn btn-icon btn-sm btn-outline-youtube"><i class="ti-trash"></i></button>';
                }
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }
            
            if( $forOrder ){
                
                $html .= '<div class="card">';
                $html .= '<form action="orders_action.php" method="POST">';
                $html .= '<input type="hidden" name="product" value="' . $item['id'] . '" />';
                $html .= '<input type="hidden" name="order" value="' . $forOrder . '" />';
                $html .= '<div class="card-body">';

                $html .= '<div class="row">';
                $html .= '<div class="form-group col-sm-12">';
                $html .= '<label for="exampleInputEmail3">Quantity</label>';
                $html .= '<input name="quantity" type="number" class="form-control" id="exampleInputEmail3" min="1" placeholder="Quantity" required />';
                $html .= '</div>';
                $html .= '</div>';

                $html .= '<div class="row">';
                $html .= '<div class="form-group col-sm-12">';
                $html .= '<label for="exampleInputEmail3">Size</label>';
                $html .= '<select name="size" class="form-control" required>';
                $html .= '<option value="">Select Product Size</option>';
                foreach ($sizes->sizes as $key => $size) {
                    $value = $size->title . ':' .$size->price;
                    $html .= '<option value="' .$value. '">';
                    $html .= $size->title;
                    $html .= '</option>';
                }
                unset( $size );
                $html .= '</select>';
                $html .= '</div>';
                $html .= '</div>';

                /* if( $item['has_sheet_count'] ){
                    $html .= '<div class="row">';
                    $html .= '<div class="form-group col-sm-12">';
                    $html .= '<label for="exampleInputEmail3">Number Of Sheets</label>';
                    $html .= '<input name="sheet_count" type="number"  min="1" class="form-control" id="exampleInputEmail3" placeholder="Number Of Sheets" required />';
                    $html .= '</div>';
                    $html .= '</div>';
                } 

                if( $item['has_addon'] ){
                    foreach ($addons as $key => $addon) {
                        if( in_array( $addon["id"], $prAddons )){
                            $value = $addon["id"] . ':' . $addon["price"];
                            $html .= '<div class="row">';
                            $html .= '<div class="col-sm-12">';
                            $html .= '<div class="list-wrapper pt-2">';
                            $html .= '<ul class="d-flex flex-column-reverse">';
                            $html .= '<li>';
                            $html .= '<div class="form-check form-check-flat">';
                            $html .= '<label class="form-check-label">';
                            $html .= '<input class="checkbox" type="checkbox" name="addons[]" value="' . $value . '">';
                            $html .= $addon['description'];
                            $html .= '</label>';
                            $html .= '</div>';
                            $html .= '<i class="remove" style="width: 100px;">';
                            $html .= 'NGN ' . $addon['price'];
                            $html .= '</i>';
                            $html .= '</li>';
                            $html .= '</ul>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '</div>';
                        }
                    }
                }*/

                $html .= '</div>';
                $html .= '<div class="card-footer">';
                $html .= '<div class="d-flex align-items-center justify-content-end">';
                $html .= '<button type="submit" name="add_product_to_order" class="btn btn-icon btn-sm btn-outline-primary"><i class="ti-plus"></i></button>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</form>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</form>';
                $html .= '</div>';
            }

            $html .= '</td>';
            $html .= '</tr>';
        }
		unset($item);

        $list["rows"] = $html;
		
		return $list;
	}

    private function getAvailableSizesCard( $sizes ) {
        $card = '<div class="col-md-12 grid-margin stretch-card" style="padding:0;">';
        $card .= '<div class="table-responsive" style="max-height: 200px; overflow-y:scroll;">';
        $card .= '<table class="table table-borderless">';
        $card .= '<thead>';
        $card .= '<tr>';
        $card .= '<th pl-0 pb-2 border-bottom>Size</th>';
        $card .= '<th pb-2 border-bottom>Price</th>';
        $card .= '</tr>';
        $card .= '</thead>';
        $card .= '<tbody>';
        foreach ($sizes->sizes as $key => $item) {
            $card .= '<tr>';
            $card .= '<td>'.$item->title.'</td>';
            $card .= '<td>'.$item->price.'</td>';
            $card .= '</tr>';
        }
        $card .= '</tbody>';
        $card .= '</table>';
        $card .= '</div>';
        $card .= '</div>';

        return $card;
    }
}

?>
