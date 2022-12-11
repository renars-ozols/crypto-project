<?php declare(strict_types=1);

namespace App\Models;

class Transaction
{
    private int $id;
    private int $userId;
    private int $coinId;
    private string $type;
    private string $coinName;
    private float $coinPrice;
    private float $amount;
    private string $createdAt;

    public function __construct(int             $id,
                                int             $userId,
                                int             $coinId,
                                string $type,
                                string          $coinName,
                                float           $coinPrice,
                                float           $amount,
                                string          $createdAt)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->coinId = $coinId;
        $this->type = $type;
        $this->coinName = $coinName;
        $this->coinPrice = $coinPrice;
        $this->amount = $amount;
        $this->createdAt = $createdAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCoinId(): int
    {
        return $this->coinId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCoinName(): string
    {
        return $this->coinName;
    }

    public function getCoinPrice(): float
    {
        return $this->coinPrice;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
