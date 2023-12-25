<?php 
include "includes/inc-system-autoload.php";  
include "includes/inc-header-main-session.php";

use SRC\DB\Entities\ClientAccount;
use SRC\DB\Entities\Order;
use SRC\DB\Entities\OrderItem;
use SRC\DB\Entities\Payment;
use SRC\Services\Account\AccountHelper;


$errorMessage     = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ( isset( $_POST['start_order'] ) ) {
		$user_id = $logged_account['id'];
        $orderNo = $_POST['order_no'];
        $client  = $_POST['client'];

        $created = (new Order())->create([
			'client'     => $client,
			'order_no'   => $orderNo,
			'amount'     => '0',
			'status'     => 'New',
			'created_by' => $user_id,
		]);

        if( $created ) {
			header( "Location: orders_manage_order.php?reference=" . $created . "&success=New order started successfully" );
			exit();
		}
		
		$errorMessage = "There was a problem with your submission, please try again.";
    }

	if ( isset( $_POST['add_product_to_order'] ) ) {
        $order    = $_POST['order'];
        $product  = $_POST['product'];
        $quantity = $_POST['quantity'];
        $sheets   = isset( $_POST['sheet_count'] ) ? $_POST['sheet_count'] : 0;
		$size     = explode( ':', $_POST['size'] )[0];
		$price    = explode( ':', $_POST['size'] )[1];
		$amount   = (int)$quantity * (float)$price;
        
		$created = (new OrderItem())->create([
			'order_id'    => $order,
			'item'        => $product,
			'item_type'   => 'product',
			'quantity'    => $quantity,
			'sheet_count' => $sheets,
			'size'        => $size,
			'price'       => $price,
			'quantity'    => $quantity,
			'amount'      => $amount,
		]);
        
		if( $created ) {
			if( isset( $_POST['addons'] ) ) {
				$addons       = $_POST['addons'];
				$createdAddon = 1;
				foreach ($addons as $key => $addon) {
					$addonId     = explode( ':', $addon )[0];
					$addonPrice  = explode( ':', $addon )[1];
					$addonAmount = (int)$quantity * (float)$addonPrice;
					
					$createdAddon = (new OrderItem())->create([
						'order_id'    => $order,
						'item'        => $addonId,
						'item_type'   => 'addon',
						'parent'      => $created,
						'quantity'    => $quantity,
						'sheet_count' => $sheets,
						'size'        => 'N/A',
						'price'       => $addonPrice,
						'quantity'    => $quantity,
						'amount'      => $addonAmount,
					]);
				}
			}
		}

		$location = $_SERVER['HTTP_REFERER'];
		if( $created ) {
			header( "Location: " . $location . "&success=Product added to order successfully" );
			exit();
		}
		
		$errorMessage = "There was a problem with your submission, please try again.";
		
	}

	if ( isset( $_POST['delete_order_item'] ) ) {
		$order    = $_POST['order'];
		$item     = $_POST['item'];
        $itemType = $_POST['item_type'];

		$orderItem = new OrderItem();

		$deleted = $orderItem->delete( $item );

		//echo $deleted;
		if( $deleted > 0 && $itemType === "product" ){
			$matches = [
				'parent' => [
					'link' => 'AND',
					'value' => $item,
				],
				'order_id' => [
					'link' => 'AND',
					'value' => $order,
				],
			];

			$deleteds = $orderItem->deleteAll( $matches );
		}

		if( $deleted ) {
			$location = $_SERVER['HTTP_REFERER'];
			header( "Location: " . $location . "&success=Product removed from order successfully" );
			exit();
		}
		
		$errorMessage = "There was a problem with your submission, please try again.";
	}

	if ( isset( $_POST['update_order'] ) ) {
		$order = $_POST['order'];
		$items = $_POST['oid'];

		$updated = 0;
		$total   = 0;
		foreach( $items as $item ) {
			$sizepr   = $_POST['oi_' . $item . '_size'];
			$quantity = $_POST['oi_' . $item . '_quantity'];
			$size     = explode( ':', $sizepr )[0];
			$price    = explode( ':', $sizepr )[1];
			$amount   = (int)$quantity * (float)$price;
			$total    = $total + $amount;

			$data     = [
				'size'     => $size,
				'quantity' => $quantity,
				'price'     => $price,
				'amount'   => $amount,
			];
			$updated = $updated + (new OrderItem())->update( $item, $data );
		}
		if( $updated >= 0 ) {
			$orderUpdateData = [
				'amount'   => $total,
			];
			$orderUpdated = (new Order())->update( $order, $orderUpdateData );
			
			header( "Location: orders_manage_order.php?reference=" . $order . "&success=Order updated successfully" );
			exit();
		}
		$errorMessage = "There was a problem updating the order, please try again.";
	}

	if ( isset( $_POST['finish_order'] ) ) {
		$order    = $_POST['reference'];
		$total    = $_POST['total'];

		$updated = (new Order())->update( $order, [
			'amount'   => $total,
			'status'   => 'Completed',
		]);

		if( $updated > 0 ) {
			header( "Location: orders_manage_order.php?reference=" . $order . "&success=Order completed successfully" );
			exit();
		}
		
		$errorMessage = "There was a problem completing the order, please try again.";
	}

	if ( isset( $_POST['accept_payment'] ) ) {
        $order_id  = $_POST['order_id'];
        $user_id   = $_POST['user_id'];
        $client_id = $_POST['client_id'];
        $amount    = $_POST['amount'];
        $method    = $_POST['pay_method'];

		if( $method === 'Account' ) {
			$account   = ( New ClientAccount() )->findBy( 'client', $client_id );
			$balance   = $account['balance'];
			if ( $balance < $amount ) {
				header( "Location: orders_manage_order.php?reference=" . $order_id . "&error=Insufficient Account Balance" );
				exit();
			}
		}

        $created = (new Payment())->create([
			'client'      => $client_id,
			'received_by' => $user_id,
			'order_id'    => $order_id,
			'method'      => $method,
			'amount'      => $amount,
		]);

        if( $created ) {
			$order = (new Order())->find( $order_id );
			if( $order ) {
				$prev_paid   = $order['total_paid']; //(new Payment())->sum('amount', [ 'order_id' => [ 'link' => 'AND', 'value'=>$order_id ] ])
				$total_paid  = doubleval( $prev_paid ) + doubleval( $amount );
				$order_total = doubleval( $order['amount'] ) - doubleval( $order['discount'] );
				$pay_status  = $total_paid === $order_total ? 'Paid' : 'Partly Paid';
				(new Order())->update($order_id,[
					'total_paid' => $total_paid,
					'pay_method' => $method,
					'pay_status' => $pay_status,
				]);
			}

			if( $method === 'Account' ){
				(new AccountHelper())->performAccountTransaction( $client_id, 'debit', $amount, $user_id, $created);
			}
			header( "Location: orders_manage_order.php?reference=" . $order_id . "&success=New payment record added successfully" );
			exit();
		}
		
		$errorMessage = "There was a problem with your submission, please try again.";
    }

	if ( isset( $_POST['apply_discount'] ) ) {
		$ord = new Order();

        $order_id = $_POST['order_id'];
        $discount = $_POST['amount'];

        $updated  = $ord->update($order_id, [
			'discount' => $discount,
		]);

        if( $updated ) {
			header( "Location: orders_manage_order.php?reference=" . $order_id . "&success=New payment record added successfully" );
			exit();
		}
		
		$errorMessage = "There was a problem with your submission, please try again.";
    }

	if ( isset( $_POST['delete'] ) ) {
		$order = $_POST['reference'];

		$delete = [
			'order_id' => [
				'link'  => 'AND',
				'value' => $order,
			],
		];

		$deleted = (new Order())->delete( $order );
		if( $deleted > 0 ) {
			(new OrderItem())->deleteAll( $delete );
			(new Payment())->deleteAll( $delete );

			header( "Location: orders_manage.php?&success=Order has been removed successfully" );
			exit();
		}
		
		$errorMessage = "There was a problem deleting the order, please try again.";
	}
}

//header( "Location: orders_manage.php?info=" . $errorMessage );
exit();
?>