<?php
namespace SRC\DB\Entities;

use SRC\DB\Entities\PagedEntity;
use SRC\DB\Entities\RegisteredUser;
use SRC\Helpers\LoggedAccount;

class Expense extends PagedEntity {
	const TABLE_NAME = 'sp_expenses';

	function __construct() {
        parent::__construct();
        $this->table_name = self::TABLE_NAME;
    }

    public function getRows( $queries ) {
        $items = $this->list( $queries );

        $rows = '';
        foreach ($items as $key => $item) {
            $user = ( new RegisteredUser() )->find( $item['created_by'] );
            $sno  = $key + 1;

            $rows .= '<tr>';
            $rows .= '<td>' . $sno . '</td>';
            $rows .= '<td>' . $item["description"] . '</td>';
            $rows .= '<td>' . $item["method"] . '</td>';
            $rows .= '<td>' . $item["amount"] . '</td>';
            $rows .= '<td>' . $user["fullname"] . '</td>';
            $rows .= '<td>' . $item["created_at"] . '</td>';
            $rows .= '<td class="text-center">';
            $rows .= '<div class="btn-group" role="group" aria-label="Basic example">';
            $rows .= '<a href="expenses_edit.php?reference=' . $item["id"] . '">';
            $rows .= '<button type="button" class="btn mr-2 btn-sm btn-outline-primary"><i class="ti-pencil"></i></button>';
            $rows .= '</a>';
            /* $rows .= '<form method="POST" action="expenses_action.php">';
            $rows .= '<input type="hidden" name="reference" value="' . $item["id"] . '">';
            $rows .= '<button type="submit" name="delete" class="btn btn-sm btn-outline-danger"><i class="ti-trash"></i></button>';
            $rows .= '</form>'; */
            if( LoggedAccount::role() === 'admin' ) {
                $rows .= '<div>';
                $rows .= '<button data-toggle="modal" data-target="#deleteModal" data-ref="' . $item['id'] . '" data-conf="Are you sure you want to delete this expenses?" data-action="expenses_action.php" class="btn btn-sm btn-rounded btn-icon btn-outline-danger"><i class="ti-trash"></i></button>';
                $rows .= '</div>';
            }
            $rows .= '</div>';
            $rows .= '</td>';
            $rows .= '</tr>';
        }

        return $rows;
    }

}
?>