<?php
namespace SRC\DB\Entities;

use SRC\DB\Entities\PagedEntity;
use SRC\Helpers\AddressHelper;
use SRC\Helpers\LoggedAccount;

class Client extends PagedEntity {
	const TABLE_NAME = 'sp_clients';

	function __construct() {
        parent::__construct();
        $this->table_name = self::TABLE_NAME;
    }

    public function getClientRows( $queries ) {
        $items = $this->list( $queries, 'name', 'ASC' );

        $rows = '';
        foreach ($items as $key => $item) {
            $rows .= '<tr>';
            $rows .= '<td>' . $item["name"] . '</td>';
            $rows .= '<td>' . $item["mobile"] . '</td>';
            $rows .= '<td>' . $item["email"] . '</td>';
            $rows .= '<td>' . AddressHelper::getAddressFromJson( $item["address"] ) . '</td>';
            $rows .= '<td>' . $item["created_at"] . '</td>';
            $rows .= '<td class="text-center">';
            $rows .= '<div class="btn-group" role="group" aria-label="Basic example">';
            $rows .= '<a href="clients_account.php?reference=' . $item["id"] . '">';
            $rows .= '<button type="button" class="btn mr-2 btn-icon btn-sm btn-outline-primary"><i class="ti-wallet"></i></button>';
            $rows .= '</a>';
            $rows .= '<a href="reports_client.php?reference=' . $item["id"] . '">';
            $rows .= '<button type="button" class="btn mr-2 btn-icon btn-sm btn-outline-primary"><i class="ti-bar-chart"></i></button>';
            $rows .= '</a>';
            $rows .= '<a href="clients_edit.php?reference=' . $item["id"] . '">';
            $rows .= '<button type="button" class="btn mr-2 btn-icon btn-sm btn-outline-primary"><i class="ti-pencil"></i></button>';
            $rows .= '</a>';
            /* $rows .= '<form method="POST" action="clients_action.php">';
            $rows .= '<input type="hidden" name="reference" value="' . $item["id"] . '">';
            $rows .= '<button type="submit" name="delete" class="btn btn-sm btn-outline-danger"><i class="ti-trash"></i></button>';
            $rows .= '</form>'; */
            if( LoggedAccount::role() === 'admin' ) {
                $rows .= '<div>';
                $rows .= '<button data-toggle="modal" data-target="#deleteModal" data-ref="' . $item['id'] . '" data-conf="Are you sure you want to delete this client?" data-action="clients_action.php" class="btn btn-sm btn-rounded btn-icon btn-outline-danger"><i class="ti-trash"></i></button>';
                $rows .= '</div>';
            }
            $rows .= '</div>';
            $rows .= '</td>';
            $rows .= '</tr>';
        }

        return $rows;
    }

    public function getClientSelectOptions( $queries ) {
        $items = $this->list( $queries, 'name', 'ASC' );

        $rows = '';
        foreach ($items as $key => $item) {
            $rows .= '<option value="' . $item["id"] . '">' . $item["name"] . '</option>';
        }

        return $rows;
    }
}

?>
