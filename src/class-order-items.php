<?php
namespace SRC\DB\Entities;

use SRC\DB\Entities\PagedEntity;

class OrderItem extends PagedEntity {
	const TABLE_NAME = 'sp_order_items';

	function __construct() {
        parent::__construct();
        $this->table_name = self::TABLE_NAME;
    }
}

?>
