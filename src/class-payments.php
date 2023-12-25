<?php
namespace SRC\DB\Entities;

use SRC\DB\Entities\PagedEntity;

class Payment extends PagedEntity {
	const TABLE_NAME = 'sp_payments';

	function __construct() {
        parent::__construct();
        $this->table_name = self::TABLE_NAME;
    }

}
?>