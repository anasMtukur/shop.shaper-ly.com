<?php
namespace SRC\DB;

/** Database Connection Class  */
class DB_Connection {
	/** LOCAL
	const DB_USER  = 'sharperly_db_manager';
	const PASSWORD = 'sb4cjp4WbL9XkQf';  */
	
	/** SITE  */
    const DB_USER  = 'shap_db';
	const PASSWORD = 'ShaperlyCreative';
	
	const DB_HOST  = 'localhost';
	const DB_NAME  = 'shaperly_oms_db';
	const DB_PORT  = 3306;

	public function getConnection(){
		$link = mysqli_connect( self::DB_HOST, self::DB_USER, self::PASSWORD, self::DB_NAME, self::DB_PORT );

		if ( !$link ) {
			echo "Error: Unable to connect to MySQL." . PHP_EOL;
			echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
			echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}
	
		return $link;
	}

	public function closeConnection( $connection ){
		mysqli_close( $connection );
	}
}
?>
