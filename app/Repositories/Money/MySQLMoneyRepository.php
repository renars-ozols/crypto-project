<?php declare(strict_types=1);

namespace App\Repositories\Money;

use App\Database;

class MySQLMoneyRepository implements MoneyRepository
{
    public function deposit(float $amount, string $userId): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder->update('users')
            ->set('money', "money + $amount")
            ->where('id = ?')
            ->setParameter(0, $userId)
            ->executeQuery();
    }

    public function withdraw(float $amount, string $userId): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder->update('users')
            ->set('money', "money - $amount")
            ->where('id = ?')
            ->setParameter(0, $userId)
            ->executeQuery();
    }
}
