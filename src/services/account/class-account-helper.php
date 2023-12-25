<?php
namespace SRC\Services\Account;

use SRC\DB\Entities\ClientAccount;
use SRC\DB\Entities\ClientAccountTransaction;

class AccountHelper {

    public function updateAccountBalance( $accountId, $transaction, $amount ) {
        $clientAccount = new ClientAccount();

        $account = $clientAccount->find( $accountId );

        $balance = $account['balance'];

        if( $transaction === 'credit' ) {
            $balance = $balance + $amount;
        }

        if( $transaction === 'debit' ) {
            $balance = $balance - $amount;
        }

        return $clientAccount->update( $accountId, [
            'balance' => $balance,
        ] );
    }

    public function performAccountTransaction( $clientId, $transaction, $amount, $receiver, $orderId ) {
        $clientAccount = new ClientAccount();

        $account   = $clientAccount->findBy( 'client', $clientId );
        $accountId = $account['id'];

        $balance   = $account['balance'];
        $created   = false;
        if ( $balance >= $amount ) {
            
            $created = (new ClientAccountTransaction())->create([
                'client'      => $clientId,
                'account'     => $accountId,
                'created_by'  => $receiver,
                'transaction' => $transaction,
                'method'      => 'Order Payment',
                'amount'      => $amount,
                'reference'   => $orderId,
            ]);
        }

        if( $created ) {
            return $this->updateAccountBalance( $accountId, $transaction, $amount );
		}

        return 0;
    }
}