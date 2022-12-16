<?php declare(strict_types=1);

namespace App\Repositories\UserCrypto;

use App\Database;
use App\Models\Collections\UserCryptoCollection;
use App\Models\UserCrypto;

class MySQLUserCryptoRepository implements UserCryptoRepository
{
    private function buildModel(array $row): UserCrypto
    {
        //TODO: nullable params
        return new UserCrypto(
            (int)$row['user_id'],
            (int)$row['coin_id'],
            $row['coin_name'],
            $row['coin_logo'],
            (float)$row['amount'],
            (int)$row['id'],
        );
    }
    public function getAll(int $userId): ?UserCryptoCollection
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $result = $queryBuilder->select('*')
            ->from('wallet')
            ->where('user_id = ?')
            ->setParameter(0, $userId)
            ->executeQuery()
            ->fetchAllAssociative();

        if ($result) {
            $portfolio = new UserCryptoCollection();
            foreach ($result as $entry) {
                $portfolio->add($this->buildModel($entry));
            }
            return $portfolio;
        }
        return null;
    }

    public function get(int $userId, int $coinId): ?UserCrypto
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $userCrypto = $queryBuilder->select('*')
            ->from('wallet')
            ->where('user_id = ?')
            ->andWhere('coin_id = ?')
            ->setParameter(0, $userId)
            ->setParameter(1, $coinId)
            ->executeQuery()
            ->fetchAssociative();
        return $userCrypto ? $this->buildModel($userCrypto): null;
    }

    public function create(UserCrypto $crypto): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder->insert('wallet')
            ->values([
                'user_id' => ':user_id',
                'coin_id' => ':coin_id',
                'coin_name' => ':coin_name',
                'coin_logo' => ':coin_logo',
                'amount' => ':amount'
            ])
            ->setParameters([
                'user_id' => $crypto->getUserId(),
                'coin_id' => $crypto->getCoinId(),
                'coin_name' => $crypto->getCoinName(),
                'coin_logo' => $crypto->getCoinLogo(),
                'amount' => $crypto->getAmount()
            ])
            ->executeQuery();
    }

    public function save(UserCrypto $crypto): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder->update('wallet')
            ->set('amount', $crypto->getAmount())
            ->where('user_id = ?')
            ->andWhere('coin_id = ?')
            ->setParameter(0, $crypto->getUserId())
            ->setParameter(1, $crypto->getCoinId())
            ->executeQuery();
    }

    public function delete(int $id): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder->delete('wallet')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->executeQuery();
    }
}
