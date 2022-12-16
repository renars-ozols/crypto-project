<?php declare(strict_types=1);

namespace App\Models;

class Transaction
{
    private int $userId;
    private int $coinId;
    private TransactionType $type;
    private string $coinName;
    private float $coinPrice;
    private float $amount;
    private ?int $id;
    private ?string $createdAt;

    public function __construct(int             $userId,
                                int             $coinId,
                                TransactionType $type,
                                string          $coinName,
                                float           $coinPrice,
                                float           $amount,
                                ?int             $id = null,
                                ?string          $createdAt = null)
    {
        $this->userId = $userId;
        $this->coinId = $coinId;
        $this->type = $type;
        $this->coinName = $coinName;
        $this->coinPrice = $coinPrice;
        $this->amount = $amount;
        $this->id = $id;
        $this->createdAt = $createdAt;
    }


    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCoinId(): int
    {
        return $this->coinId;
    }

    public function getType(): TransactionType
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }
}
