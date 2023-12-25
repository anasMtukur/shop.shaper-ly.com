<?php
namespace SRC\DB\Entities;

use SRC\DB\Entities\PagedEntity;

class ProductSize extends PagedEntity {
	const TABLE_NAME = 'sp_product_sizes';

	function __construct() {
        parent::__construct();
        $this->table_name = self::TABLE_NAME;
    }
}

?>
