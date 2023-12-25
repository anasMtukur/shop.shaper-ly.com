<?php
namespace SRC\DB\Entities;

use SRC\DB\Entities\DBEntity;

class ClientAccount extends DBEntity {
	const TABLE_NAME = 'sp_client_accounts';

	function __construct() {
        parent::__construct();
        $this->table_name = self::TABLE_NAME;
    }

}
?>