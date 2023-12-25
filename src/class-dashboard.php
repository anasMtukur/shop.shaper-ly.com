<?php
namespace SRC\DB\Entities;

use SRC\DB\Entities\Product;
use SRC\DB\Entities\OrderItem;
use SRC\DB\Entities\Client;
use SRC\DB\Entities\Order;
use SRC\DB\Entities\Payment;

class Dashboard {
    const DAY_QUERY = 'WHERE `created_at` >= CURDATE() AND `created_at` < CURDATE() + INTERVAL 1 DAY';
    const COMPLETED = [
        'status' => [
            'link'=>'AND', 
            'value'=>'Completed'
        ]
    ];
    public function getDashboardCounters() {
        
        return [
            'products'         => (new Product())->count(),
            'clients'          => (new Client())->count(),
            'orders'           => (new Order())->count(),
            'totalOrderAmount' => (new Order())->sum( 'amount', self::COMPLETED ),
            'todayOrderAmount' => (new Order())->sum( 'amount', self::DAY_QUERY ),
            'totalPayment'     => (new Payment())->sum( 'amount', '' ),
            'todayPayment'     => (new Payment())->sum( 'amount', self::DAY_QUERY ),
        ];
    }

    public function getDashboardReports() {
        $payment = new Payment();
        $order   = new Order();
        $product    = new Product();

        $paymentsTotal = $payment->sum( 'amount', self::DAY_QUERY );
        $ordersTotal   = $order->sum( 'amount', self::DAY_QUERY );
        $paymentsToday = $payment->list( self::DAY_QUERY );
        $OrdersToday   = $order->list( self::DAY_QUERY );

        $paymentCategories = [
            'Cash' => 0,
            'POS' => 0,
            'Transfer' => 0,
            'Deposit' => 0,
        ];

        foreach( $paymentsToday as $item ) {
            if( isset( $paymentCategories[ $item['method'] ] ) ){
                $paymentCategories[ $item['method'] ] = $paymentCategories[ $item['method'] ] + $item['amount'];
            }else{
                $paymentCategories[ $item['method'] ] = $item['amount'];
            }
        }
        unset( $item );

        $orderCategories = [];
        $OrderItemsToday = (new OrderItem())->list( self::DAY_QUERY );
        foreach( $OrderItemsToday as $item ) { 
            $itemProduct = $product->find( $item["item"] );           
            $itemName = $itemProduct['description'];
            if( isset( $orderCategories[ $item['item'] ] ) ){
                $orderCategories[ $itemName ] = $orderCategories[ $itemName ] + $item['amount'];
            }else{
                $orderCategories[ $itemName ] = $item['amount'];
            }
        }
        unset( $item );
        
        arsort($orderCategories);
        arsort($paymentCategories);

        $report = [
            'orders' => [
                'total' => $ordersTotal,
                'count' => $order->count( self::DAY_QUERY ),
                'list'  => $OrdersToday,
                'cats'  => $orderCategories,
            ],
            'payments' => [
                'total' => $paymentsTotal,
                'count' => $payment->count( self::DAY_QUERY ),
                'list'  => $paymentsToday,
                'cats'  => $paymentCategories,
            ],
        ];
        
        return $report;
    }
}

?>