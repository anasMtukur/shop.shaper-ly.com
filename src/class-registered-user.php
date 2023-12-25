<?php
namespace SRC\DB\Entities;

use SRC\DB\Entities\DBEntity;
use SRC\Helpers\CodeGenerator;

class RegisteredUser extends DBEntity {
	const TABLE_NAME = 'sp_system_registered_users';

	function __construct() {
        parent::__construct();
        $this->table_name = self::TABLE_NAME;
    }

	public function generateNewResetToken() {
		$cg = new CodeGenerator(self::TABLE_NAME);
		$code = $cg->randomCodeOfLength( 15 );
		return $code;
	}
}