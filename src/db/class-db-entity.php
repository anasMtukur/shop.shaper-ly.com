<?php
namespace SRC\DB\Entities;

use SRC\DB\DB_Connection;

abstract class DBEntity {

	/** @var DB_Connection */
	private $db;

	public $table_name;

	public function __construct() {
		$this->db = new DB_Connection();
	}

	public function setTableName($table_name) {
		$this->table_name = $table_name;
	}

	public function list( $queries = [], $orderBy = 'created_at', $orderDir = 'DESC' ) {
		$items  = [];
		$con    = $this->db->getConnection();
		$search = $this->processQuerySearch( $queries );
		
		$query = "SELECT * FROM `{$this->table_name}`" . $search . " ORDER BY `" . $orderBy . "` " . $orderDir;
		//echo $query;
		if( $result = mysqli_query( $con, $query ) ) {
			$items = $result->fetch_all( MYSQLI_ASSOC );
		}

		$this->db->closeConnection( $con );

		return $items;
	}

	public function find( $id ){
		$items = $this->list( [ 'id' => [ 'link' => 'and', 'value' => $id ] ] );
		
		return current( $items );
	}

	public function findBy( $col, $val ){
		$items = $this->list( [ $col => [ 'link' => 'and', 'value' => $val ] ] );
		
		return current( $items );
	}

	public function queryList( $query ) {
		$items  = [];
		$con    = $this->db->getConnection();
		
		$query = "SELECT * FROM `{$this->table_name}`" . $query;
		//echo $query;
		
		if( $result = mysqli_query( $con, $query ) ) {
			$items = $result->fetch_all( MYSQLI_ASSOC );
		}

		$this->db->closeConnection( $con );

		return $items;
	}

	public function create( $columnsArr = [], $checks = [] ) {
		
		$columns = '';
		$values  = '';
		$created = 0;
		$i = 0;

		foreach ($columnsArr as $column => $value) {
			$columns .= '`' . $column . '`';
			if ( $i < count( $columnsArr ) - 1 ) {
				$columns .= ', ';
			}

			$values .= "'" . $value . "'";
			if ( $i < count( $columnsArr ) - 1 ) {
				$values .= ', ';
			}
			$i += 1;
		}

		$con     = $this->db->getConnection();
		$query = "INSERT INTO `{$this->table_name}` ( {$columns} ) VALUES ( {$values} )";
		//echo $query;
		if (mysqli_query($con, $query)){
            $created = mysqli_insert_id($con);
		}else{
			$created = false;
			die(mysqli_error($con));
		}
		$this->db->closeConnection( $con );

		return $created;
	}

	public function update( $itemId, $columnsArr = [], $checks = [] ) {
		
		$columns = '';
		$created = 0;
		$i = 0;

		foreach ($columnsArr as $column => $value) {
			$columns .= "`" . $column . "` = '" . $value . "'";
			if ( $i < count( $columnsArr ) - 1 ) {
				$columns .= ', ';
			} else {

			}

			$i += 1;
		}

		$con     = $this->db->getConnection();
		$query = "UPDATE `{$this->table_name}` SET {$columns} WHERE `id` = " . $itemId . "";
		//echo $query;
		if (mysqli_query($con, $query)){
            $created = mysqli_affected_rows($con);
		}else{
			$created = 0;
			mysqli_error($con);
		}
		$this->db->closeConnection( $con );

		return $created;
	}

	public function delete( $itemId ) {
		$con     = $this->db->getConnection();
		$query = "DELETE FROM `{$this->table_name}` WHERE `id` = " . $itemId . "";
		if (mysqli_query($con, $query)){
            $deleted = mysqli_affected_rows($con);
		}else{
			$deleted = 0;
			mysqli_error($con);
		}
		$this->db->closeConnection( $con );

		return $deleted;
	}

	public function deleteAll( $queries ) {
		$query = $this->processQuerySearch( $queries );
		$con   = $this->db->getConnection();
		$query = "DELETE FROM `{$this->table_name}` " . $query;
		//echo $query;
		if (mysqli_query($con, $query)){
            $deleted = mysqli_affected_rows($con);
		}else{
			$deleted = 0;
			mysqli_error($con);
		}
		$this->db->closeConnection( $con );

		return $deleted;
	}

	public function count( $queries = [] ) {
		$con    = $this->db->getConnection();
		$search = $this->processQuerySearch( $queries );
		
		$query = "SELECT COUNT(`id`) as count FROM `{$this->table_name}`" . $search;
		//echo $query;
		$items = 0;
		if( $result = mysqli_query( $con, $query ) ) {
			$items = $result->fetch_all( MYSQLI_ASSOC );
		}

		$this->db->closeConnection( $con );

		$sum = $items[0]['count'];

		return $sum ?: 0;
	}

	public function sum( $column, $queries = [] ) {
		$con    = $this->db->getConnection();
		$search = $this->processQuerySearch( $queries );
		
		$query = "SELECT SUM(`{$column}`) as sum FROM `{$this->table_name}` " . $search;
		//echo $query;
		$items = 0;
		if( $result = mysqli_query( $con, $query ) ) {
			$items = $result->fetch_all( MYSQLI_ASSOC );
		}
		$this->db->closeConnection( $con );

		$sum = $items[0]['sum'];

		return $sum ?: 0;
	}

	public function processQuerySearch( $queries ) {

		if( is_string( $queries ) ) {
			return $queries;
		}
		
		$search = '';
		if ( $queries ) {
			$i = 0;
			foreach ($queries as $key => $query) {
				if ( $i === 0 ) {
					$search .= ' WHERE'; 
				}

				if ( $i > 0 ) {
					$search .= $query["link"]; 
				}

				$search .= " " . $key . "='" . $query["value"] . "'";

				$i =+ 1;
			}
		}

		return $search;
	}

	public function query( $query ) {
		$items = [];
		$con   = $this->db->getConnection();
		//echo $query;
		
		if( $result = mysqli_query( $con, $query ) ) {
			$items = $result->fetch_all( MYSQLI_ASSOC );
		}

		$this->db->closeConnection( $con );

		return $items;
	}
}
?>