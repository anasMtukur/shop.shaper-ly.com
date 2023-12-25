<?php
namespace SRC\Services\UI;

use SRC\DB\Entities\Expense;
use SRC\DB\Entities\Product;
use SRC\DB\Entities\OrderItem;
use SRC\DB\Entities\Order;
use SRC\DB\Entities\Payment;

class Reports{
    const COMPLETED = [
        'status' => [
            'link'=>'AND', 
            'value'=>'Completed'
        ]
    ];

    //2023-05-21
    public function getDailyReports( $date = 'CURDATE()' ) {
        $query = " WHERE `created_at` >= '" . $date . "' AND `created_at` < '" . $date . "' + INTERVAL 1 DAY";
        $report = $this->getQueriedReports( $query );

        return $report;
    }

    public function getMonthlyReports( $date = 'CURDATE()' ) {
        $query = " WHERE MONTH(`created_at`) = MONTH('" . $date . "')";
        $report = $this->getQueriedReports( $query );

        return $report;
    }

    public function getYearlyReports( $date = 'CURDATE()' ) {
        $query  = " WHERE YEAR(`created_at`) = YEAR('" . $date . "')";
        $report = $this->getQueriedReports( $query );

        return $report;
    }

    public function getPeriodicReports( $startDate = 'CURDATE()', $endDate = 'CURDATE()' ) {
        $query = " WHERE `created_at` >= '" . $startDate . "' AND `created_at` < '" . $endDate . "' + INTERVAL 1 DAY";
        $report = $this->getQueriedReports( $query );

        return $report;
    }

    public function getClientReports( $clientId ) {
        $query = " WHERE `client` = '" . $clientId . "'";
        $report = $this->getQueriedReports( $query );

        return $report;
    }

    private function getQueriedReports( $query ) {
        $payment = new Payment();
        $order   = new Order();
        $expense = new Expense();
        
        $reports = [
            "orders"   => [
                "count"    => $order->count( $query ),
                "total"    => $order->sum( 'amount', $query ),
                "discount" => $order->sum( 'discount', $query ),
                "list"     => [],
            ],
            "payments" => [
                "count"   => $payment->count( $query ),
                "total"   => $payment->sum( 'amount', $query ),
                "list"    => [],
                "methods" => [],
            ],
            "expenses" => [
                "count"   => $expense->count( $query ),
                "total"   => $expense->sum( 'amount', $query ),
                "list"    => [],
                "methods" => [],
            ]
        ];

        $OrdersList   = $order->list( $query );
        $reports["orders"]["list"] = $OrdersList;

        $paymentsList = $payment->list( $query );
        
        $paymentCategories = [
            'Cash' => 0,
            'POS' => 0,
            'Transfer' => 0,
            'Deposit' => 0,
        ];
        foreach( $paymentsList as $item ) {
            if( isset( $paymentCategories[ $item['method'] ] ) ){
                $paymentCategories[ $item['method'] ] = $paymentCategories[ $item['method'] ] + $item['amount'];
            }else{
                $paymentCategories[ $item['method'] ] = $item['amount'];
            }
        }
        unset( $item );

        $reports["payments"]["list"]    = $paymentsList;
        $reports["payments"]["methods"] = $paymentCategories;

        $expenseList  = $expense->list( $query );
        
        $expenseCategories = [
            'Cash' => 0,
            'POS' => 0,
            'Transfer' => 0,
            'Deposit' => 0,
        ];
        foreach( $expenseList as $item ) {
            if( isset( $expenseCategories[ $item['method'] ] ) ){
                $expenseCategories[ $item['method'] ] = $expenseCategories[ $item['method'] ] + $item['amount'];
            }else{
                $expenseCategories[ $item['method'] ] = $item['amount'];
            }
        }
        unset( $item );

        arsort( $paymentCategories );
        arsort( $expenseCategories );

        $reports["expenses"]["list"]    = $expenseList;
        $reports["expenses"]["methods"] = $expenseCategories;

        return $reports;
    }
}

?>