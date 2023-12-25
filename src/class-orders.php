<?php
namespace SRC\DB\Entities;

use SRC\DB\Entities\PagedEntity;
use SRC\Helpers\LoggedAccount;

class Order extends PagedEntity {
	const TABLE_NAME = 'sp_orders';

	function __construct() {
        parent::__construct();
        $this->table_name = self::TABLE_NAME;
    }

    public function getPagedList( $size = 50, $offsetId = 0, $offsetDate = '2023-01-01 00:00:01', $isPaged = true,  $matches = [], $listOnly = false ) {
		$list  = $this->pagedList( $size, $offsetId, $offsetDate, $isPaged,  $matches );
		$items = $list["items"];
		
		if( $listOnly ) {
			return $list;
		}

        $html = '';
        if( count( $items ) > 0 ) {
            foreach ( $items as $item ) {
                $client = ( new Client() )->find( $item["client"] );
                $total  = $item["amount"] - $item["discount"];
                $html .= '<tr>';
                $html .= '<td>' . $item["order_no"] . '</td>';
                $html .= '<td>' . $client["name"] . '</td>';
                /* $html .= '<td class="text-center">' . money_format( "&#8358; %!n", $item["amount"] ) . '</td>';
                $html .= '<td class="text-center">' . money_format( "&#8358; %!n", $item["discount"] ) . '</td>'; */
                $html .= '<td class="text-center">' . money_format( "&#8358; %!n", $total ) . '</td>';
                $html .= '<td class="text-center"><label class="badge badge-warning">' . $item["status"] . '</label></td>';
                $html .= '<td class="text-right">';
                $html .= '<div class="btn-group" role="group" aria-label="Basic example">';
                $html .= '<a href="orders_manage_order.php?reference=' . $item["id"] . '">';
                $html .= '<button type="button" class="btn btn-rounded btn-icon mr-2 btn-sm btn-outline-primary"><i class="ti-menu-alt"></i></button>';
                $html .= '</a>';
                if( $item["status"] === 'Completed' ) {
                    $html .= '<a href="orders_summary_order.php?reference=' . $item["id"] . '">';
                    $html .= '<button type="button" class="btn btn-rounded btn-icon mr-2 btn-sm btn-outline-success"><i class="ti-printer"></i></button>';
                    $html .= '</a>';
                }
                /* $html .= '<form method="POST" action="orders_action.php">';
                $html .= '<input type="hidden" name="reference" value="' . $item["id"] . '">';
                $html .= '<button type="submit" name="delete_order" class="btn btn-sm btn-rounded btn-icon btn-outline-danger"><i class="ti-trash"></i></button>';
                $html .= '</form>'; */
                //if( LoggedAccount::role() === 'admin' ) {
                $html .= '<div>';
                $html .= '<button data-toggle="modal" data-target="#deleteModal" data-ref="' . $item['id'] . '" data-conf="Are you sure you want to delete this order?" data-action="orders_action.php" class="btn btn-sm btn-rounded btn-icon btn-outline-danger"><i class="ti-trash"></i></button>';
                $html .= '</div>';
                //}
                $html .= '</div>';
                $html .= '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr>';
            $html .= '<td colspan="8">';
            $html .= '<p class="text-center">No orders found here!</p>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        unset($item);

        $list["rows"] = $html;
		
		return $list;
    }

    public function getList( $queries, $listOnly = false ) {
		$items  = $this->queryList( $queries );
		
        $list = [];
		if( $listOnly ) {
			return $items;
		}
        $list["items"] = $items;

        $html = '';
        if( count( $items ) > 0 ) {
            foreach ( $items as $item ) {
                $client = ( new Client() )->find( $item["client"] );
                $total  = $item["amount"] - $item["discount"];
                
                $html .= '<tr>';
                $html .= '<td>' . $item["order_no"] . '</td>';
                $html .= '<td>' . $client["name"] . '</td>';
                /* $html .= '<td class="text-center">' . money_format( "&#8358; %!n", $item["amount"] ) . '</td>';
                $html .= '<td class="text-center">' . money_format( "&#8358; %!n", $item["discount"] ) . '</td>'; */
                $html .= '<td class="text-center">' . money_format( "&#8358; %!n", $total ) . '</td>';
                $html .= '<td class="text-center"><label class="badge badge-warning">' . $item["status"] . '</label></td>';
                $html .= '<td class="text-right">';
                $html .= '<div class="btn-group" role="group" aria-label="Basic example">';
                $html .= '<a href="orders_manage_order.php?reference=' . $item["id"] . '">';
                $html .= '<button type="button" class="btn btn-rounded btn-icon mr-2 btn-sm btn-outline-primary"><i class="ti-menu-alt"></i></button>';
                $html .= '</a>';
                if( $item["status"] === 'Completed' ) {
                    $html .= '<a href="orders_summary_order.php?reference=' . $item["id"] . '">';
                    $html .= '<button type="button" class="btn btn-rounded btn-icon mr-2 btn-sm btn-outline-success"><i class="ti-printer"></i></button>';
                    $html .= '</a>';
                }
                /* $html .= '<form method="POST" action="orders_action.php">';
                $html .= '<input type="hidden" name="reference" value="' . $item["id"] . '">';
                $html .= '<button type="submit" name="delete_order" class="btn btn-sm btn-rounded btn-icon btn-outline-danger"><i class="ti-trash"></i></button>';
                $html .= '</form>'; */
                $html .= '<div>';
                $html .= '<button data-toggle="modal" data-target="#deleteModal" data-ref="' . $item['id'] . '" data-conf="Are you sure you want to delete this order?" data-action="orders_action.php" class="btn btn-sm btn-rounded btn-icon btn-outline-danger"><i class="ti-trash"></i></button>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr>';
            $html .= '<td colspan="8">';
            $html .= '<p class="text-center">No orders found here!</p>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        unset($item);

        $list["rows"] = $html;
		
		return $list;
    }
}

?>
