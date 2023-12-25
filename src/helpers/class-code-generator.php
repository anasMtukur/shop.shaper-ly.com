<?php
namespace SRC\Helpers;

use SRC\DB\Entities\DBEntity;

class CodeGenerator {
    /** @var DBEntity */
	private $entity;

    function __construct( $entity ) {
        $this->entity = $entity;
    }

    public function randomCodeOfLength( $length ){
		$code = "";
		$characters = array_merge(range('A','Z'), range('0','9'), range('a','z'));
		$max = count($characters) - 1;
		for ($i = 0; $i < $length; $i++) {
			$rand = mt_rand(0, $max);
			$code .= $characters[$rand];
		}
		
		if( !$this->checkIfCodeExist( $code ) ){
			return $code;
		}
			
		return $this->randomCodeOfLength( $length );
	}

	private function checkIfCodeExist( $code ){
		$existingOrderWithCode = $this->entity->list([
			'code' => [
				'link'  => 'AND',
				'value' => $code
			],
		]);

		return count( $existingOrderWithCode ) > 0;
	}
}