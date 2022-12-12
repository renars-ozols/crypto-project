<?php declare(strict_types=1);

namespace App\Repositories\BuySellCrypto;

use App\Database;
use App\Models\TransactionType;
use App\Services\BuySellCrypto\BuySellCryptoServiceRequest;

class MySQLBuySellCryptoRepository implements BuySellCryptoRepository
{
    public function buyCrypto(BuySellCryptoServiceRequest $request): void
    {
        // deduct the amount from the user's balance
        $amountToDeduct = $request->getAmount() * $request->getCoinPrice();
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder->update('users')
            ->set('money', "money - $amountToDeduct")
            ->where('id = ?')
            ->setParameter(0, $request->getUserId())
            ->executeQuery();

        $queryBuilder->resetQueryParts();

        // add the amount to the user's crypto balance
        $result = $queryBuilder->update('wallet')
            ->set('amount', "amount + {$request->getAmount()}")
            ->where('coin_id = ?')
            ->andWhere('user_id = ?')
            ->setParameter(0, $request->getCoinId())
            ->setParameter(1, $request->getUserId())
            ->executeQuery();

        if ($result->rowCount() == 0) {
            $queryBuilder->resetQueryParts();
            // if the user doesn't have any crypto of this type, add it to the wallet
            $queryBuilder->insert('wallet')
                ->values([
                    'user_id' => ':user_id',
                    'coin_id' => ':coin_id',
                    'coin_name' => ':coin_name',
                    'coin_logo' => ':coin_logo',
                    'amount' => ':amount'
                ])
                ->setParameters([
                    'user_id' => $request->getUserId(),
                    'coin_id' => $request->getCoinId(),
                    'coin_name' => $request->getCoinName(),
                    'coin_logo' => $request->getCoinLogo(),
                    'amount' => $request->getAmount()
                ])
                ->executeQuery();
        }

        // create a transaction
        $queryBuilder->insert('transactions')
            ->values([
                'user_id' => ':user_id',
                'coin_id' => ':coin_id',
                'type' => ':type',
                'coin_name' => ':coin_name',
                'coin_price' => ':coin_price',
                'amount' => ':amount'
            ])
            ->setParameters([
                'user_id' => $request->getUserId(),
                'coin_id' => $request->getCoinId(),
                'type' => TransactionType::BUY,
                'coin_name' => $request->getCoinName(),
                'coin_price' => $request->getCoinPrice(),
                'amount' => $request->getAmount()
            ])
            ->executeQuery();
    }

    public function sellCrypto(BuySellCryptoServiceRequest $request): void
    {
        // add the amount to the user's balance
        $amountToAdd = $request->getAmount() * $request->getCoinPrice();
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder->update('users')
            ->set('money', "money + $amountToAdd")
            ->where('id = ?')
            ->setParameter(0, $request->getUserId())
            ->executeQuery();

        $queryBuilder->resetQueryParts();

        // deduct the amount from the user's crypto balance
        $queryBuilder->update('wallet')
            ->set('amount', "amount - {$request->getAmount()}")
            ->where('coin_id = ?')
            ->andWhere('user_id = ?')
            ->setParameter(0, $request->getCoinId())
            ->setParameter(1, $request->getUserId())
            ->executeQuery();

        $queryBuilder->resetQueryParts();

        $amountLeft = $queryBuilder->select('amount')
            ->from('wallet')
            ->where('coin_id = ?')
            ->andWhere('user_id = ?')
            ->setParameter(0, $request->getCoinId())
            ->setParameter(1, $request->getUserId())
            ->executeQuery()
            ->fetchAssociative();

        // if the user has no more coins of this type, delete the row
        if ($amountLeft['amount'] == 0) {
            $queryBuilder->resetQueryParts();
            $queryBuilder->delete('wallet')
                ->where('coin_id = ?')
                ->andWhere('user_id = ?')
                ->setParameter(0, $request->getCoinId())
                ->setParameter(1, $request->getUserId())
                ->executeQuery();
        }

        // create a transaction
        $queryBuilder->insert('transactions')
            ->values([
                'user_id' => ':user_id',
                'coin_id' => ':coin_id',
                'type' => ':type',
                'coin_name' => ':coin_name',
                'coin_price' => ':coin_price',
                'amount' => ':amount'
            ])
            ->setParameters([
                'user_id' => $request->getUserId(),
                'coin_id' => $request->getCoinId(),
                'type' => TransactionType::SELL,
                'coin_name' => $request->getCoinName(),
                'coin_price' => $request->getCoinPrice(),
                'amount' => $request->getAmount()
            ])
            ->executeQuery();
    }
}
