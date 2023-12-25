<?php
namespace SRC\DB\Entities;

use SRC\DB\Entities\PagedEntity;

class ClientAccountTransaction extends PagedEntity {
	const TABLE_NAME = 'sp_client_account_transactions';

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
                $html .= '<tr>';
                $html .= '<td><span style="text-transform: uppercase;">' . $item["transaction"] . '</span></td>';
                $html .= '<td>' . $item["method"] . '</td>';
                $html .= '<td class="text-center">' . money_format( "&#8358; %!n", $item["amount"] ) . '</td>';
                $html .= '<td class="text-center">' . $item["created_at"] . '</td>';
                $html .= '<td class="text-right">';
                $html .= '<div class="btn-group" role="group" aria-label="Basic example">';
                $html .= '<form method="POST" action="clients_account_action.php">';
                $html .= '<input type="hidden" name="reference" value="' . $item["id"] . '">';
                $html .= '<button type="submit" name="delete" title="Cancel Transactions" class="btn btn-sm btn-rounded btn-icon btn-outline-danger"><i class="fa fa-times"></i></button>';
                $html .= '</form>';
                $html .= '</div>';
                $html .= '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr>';
            $html .= '<td colspan="5">';
            $html .= '<p class="text-center">There are no transactions in this account!</p>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        unset($item);

        $list["rows"] = $html;
		
		return $list;
    }

}
?>