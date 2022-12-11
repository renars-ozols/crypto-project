<?php declare(strict_types=1);

namespace App\Models;

class Portfolio
{
    private int $id;
    private int $userId;
    private int $coinId;
    private string $coinName;
    private string $coinLogo;
    private float $amount;

    public function __construct(int $id, int $userId, int $coinId, string $coinName, string $coinLogo, float $amount)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->coinId = $coinId;
        $this->coinName = $coinName;
        $this->coinLogo = $coinLogo;
        $this->amount = $amount;
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

    public function getCoinName(): string
    {
        return $this->coinName;
    }

    public function getCoinLogo(): string
    {
        return $this->coinLogo;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
