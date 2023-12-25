<?php
namespace SRC\DB\Entities;

use SRC\DB\Entities\DBEntity;

class PagedEntity extends DBEntity {

	public function pagedList( $size = 50, $offsetId = 0, $offsetDate = '2023-01-01 00:00:01', $isPaged = true,  $matches = [] ) {
		$query = $this->processQuerySearch( $matches );

		if( $isPaged ) {
			if ( $query !== '' ) {
				$query .= " AND";
			}else{
				$query .= " WHERE";
			}
			$query .= " (`created_at`, `id`) > ('" . $offsetDate . "', " . $offsetId . ") ORDER BY `created_at`,`id` ASC LIMIT " . $size;
		}
		$items = $this->queryList( $query );
		$count = $this->count( $matches );

		$nextOffsetDate = null;
		$nextOffsetId   = null;
		
		if( $items ) {
			$nextOffsetDate = end( $items )["created_at"];
			$nextOffsetId   = end( $items )["id"];
		}
		
		$list = [
			'offset'   => [
				'id'   => $nextOffsetId,
				'date' => $nextOffsetDate,
			],
			'count'    => $count,
		];

		$list['items'] = $items;

		return $list;
	}
}

?>