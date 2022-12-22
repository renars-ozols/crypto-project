<?php declare(strict_types=1);

namespace App\Repositories\ShortSell;

use App\Database;
use App\Models\Collections\ShortSellOrderCollection;
use App\Models\ShortSellOrder;
use App\Models\ShortSellOrderType;
use App\Services\ShortCrypto\ShortSellOrderRequest;
use Carbon\Carbon;

class MySQLShortSellRepository implements ShortSellRepository
{
    private function buildModel(array $row): ShortSellOrder
    {
        return new ShortSellOrder(
            (int)$row['user_id'],
            (int)$row['coin_id'],
            $row['coin_name'],
            $row['coin_logo'],
            (float)$row['quantity'],
            (float)$row['total_borrowed'],
            (float)$row['total_repaid'],
            (float)$row['profit_loss'],
            ShortSellOrderType::from($row['status']),
            (int)$row['id'],
            $row['updated_at'],
            $row['closed_at'],
            $row['created_at']
        );
    }

    public function create(ShortSellOrderRequest $shortSellOrderRequest): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder
            ->insert('short_sell_orders')
            ->values([
                'user_id' => '?',
                'coin_id' => '?',
                'coin_name' => '?',
                'coin_logo' => '?',
                'quantity' => '?',
                'total_borrowed' => '?',
            ])
            ->setParameter(0, $shortSellOrderRequest->getUserId())
            ->setParameter(1, $shortSellOrderRequest->getCoinId())
            ->setParameter(2, $shortSellOrderRequest->getCoinName())
            ->setParameter(3, $shortSellOrderRequest->getCoinLogo())
            ->setParameter(4, $shortSellOrderRequest->getQuantity())
            ->setParameter(5, $shortSellOrderRequest->getTotalBorrowed())
            ->executeQuery();
    }

    public function getOpenOrder(int $userId, int $coinId): ?ShortSellOrder
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $result = $queryBuilder
            ->select('*')
            ->from('short_sell_orders')
            ->where('user_id = ?')
            ->andWhere('coin_id = ?')
            ->andWhere('status = ?')
            ->setParameter(0, $userId)
            ->setParameter(1, $coinId)
            ->setParameter(2, ShortSellOrderType::OPEN()->getValue())
            ->executeQuery()
            ->fetchAssociative();
        if ($result) {
            return $this->buildModel($result);
        }
        return null;
    }

    public function update(ShortSellOrder $shortSellOrder): void
    {
        //TODO: Set Carbon timezone in index.php
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder
            ->update('short_sell_orders')
            ->set('quantity', '?')
            ->set('total_borrowed', '?')
            ->set('total_repaid', '?')
            ->set('profit_loss', '?')
            ->set('status', '?')
            ->set('updated_at', '?')
            ->set('closed_at', '?')
            ->where('id = ?')
            ->setParameter(0, $shortSellOrder->getQuantity())
            ->setParameter(1, $shortSellOrder->getTotalBorrowed())
            ->setParameter(2, $shortSellOrder->getTotalRepaid())
            ->setParameter(3, $shortSellOrder->getProfitLoss())
            ->setParameter(4, $shortSellOrder->getStatus()->getValue())
            ->setParameter(5, Carbon::now('Europe/Riga')->toDateTimeString())
            ->setParameter(6, $shortSellOrder->getClosedAt())
            ->setParameter(7, $shortSellOrder->getId())
            ->executeQuery();
    }

    public function getAllOpenShortSellOrders(int $userId): ?ShortSellOrderCollection
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $result = $queryBuilder
            ->select('*')
            ->from('short_sell_orders')
            ->where('user_id = ?')
            ->andWhere('status = ?')
            ->setParameter(0, $userId)
            ->setParameter(1, ShortSellOrderType::OPEN()->getValue())
            ->executeQuery()
            ->fetchAllAssociative();
        if ($result) {
            $shortSellOrderCollection = new ShortSellOrderCollection();
            foreach ($result as $row) {
                $shortSellOrderCollection->add($this->buildModel($row));
            }
            return $shortSellOrderCollection;
        }
        return null;
    }

    public function getAllClosedShortSellOrders(int $userId): ?ShortSellOrderCollection
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $result = $queryBuilder
            ->select('*')
            ->from('short_sell_orders')
            ->where('user_id = ?')
            ->andWhere('status = ?')
            ->setParameter(0, $userId)
            ->setParameter(1, ShortSellOrderType::CLOSED()->getValue())
            ->executeQuery()
            ->fetchAllAssociative();
        if ($result) {
            $shortSellOrderCollection = new ShortSellOrderCollection();
            foreach ($result as $row) {
                $shortSellOrderCollection->add($this->buildModel($row));
            }
            return $shortSellOrderCollection;
        }
        return null;
    }
}
