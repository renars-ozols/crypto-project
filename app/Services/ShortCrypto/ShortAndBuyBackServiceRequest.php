<?php declare(strict_types=1);

namespace App\Services\ShortCrypto;

class ShortAndBuyBackServiceRequest
{
    private int $userId;
    private int $coinId;
    private float $amount;

    public function __construct(int $userId, int $coinId, float $amount)
    {
        $this->userId = $userId;
        $this->coinId = $coinId;
        $this->amount = $amount;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCoinId(): int
    {
        return $this->coinId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
