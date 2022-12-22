<?php declare(strict_types=1);

namespace App\Repositories\Transactions;

use App\Database;
use App\Models\Collections\TransactionCollection;
use App\Models\Transaction;
use App\Models\TransactionType;

class MySQLTransactionsRepository implements TransactionsRepository
{
    public function getAll(int $userId): ?TransactionCollection
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $result = $queryBuilder->select('*')
            ->from('transactions')
            ->where('user_id = ?')
            ->setParameter(0, $userId)
            ->addOrderBy('created_at', 'DESC')
            ->executeQuery()
            ->fetchAllAssociative();

        if ($result) {
            $transactions = new TransactionCollection();
            foreach ($result as $transaction) {
                $transactions->addTransaction($this->buildModel($transaction));
            }
            return $transactions;
        }
        return null;
    }

    private function buildModel(array $row): Transaction
    {
        return new Transaction(
            (int)$row['user_id'],
            (int)$row['coin_id'],
            TransactionType::from($row['type']),
            $row['coin_name'],
            (float)$row['coin_price'],
            (float)$row['amount'],
            (int)$row['id'],
            $row['created_at']
        );
    }

    public function create(Transaction $transaction): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder->insert('transactions')
            ->values([
                'user_id' => ':user_id',
                'coin_id' => ':coin_id',
                'type' => ':type',
                'coin_name' => ':coin_name',
                'coin_price' => ':coin_price',
                'amount' => ':amount',
                'total' => ':total',
            ])
            ->setParameters([
                'user_id' => $transaction->getUserId(),
                'coin_id' => $transaction->getCoinId(),
                'type' => $transaction->getType(),
                'coin_name' => $transaction->getCoinName(),
                'coin_price' => $transaction->getCoinPrice(),
                'amount' => $transaction->getAmount(),
                'total' => $transaction->getTotal(),
            ])
            ->executeQuery();
    }

    public function getAverageBuyingPrices(int $userId): array
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();

        return $queryBuilder->select('coin_id, AVG(coin_price) as average_price')
            ->from('transactions')
            ->where('user_id = ?')
            ->andWhere('type = ?')
            ->setParameter(0, $userId)
            ->setParameter(1, TransactionType::BUY())
            ->groupBy('coin_id')
            ->executeQuery()
            ->fetchAllKeyValue();
    }
}
