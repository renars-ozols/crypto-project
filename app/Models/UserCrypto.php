<?php declare(strict_types=1);

namespace App\Models;

class UserCrypto
{
    private int $userId;
    private int $coinId;
    private string $coinName;
    private string $coinLogo;
    private float $amount;
    private ?int $id;
    private ?float $averagePrice;
    private ?float $currentPrice;

    public function __construct(int    $userId,
                                int    $coinId,
                                string $coinName,
                                string $coinLogo,
                                float  $amount,
                                ?int   $id = null,
                                ?float $averagePrice = null,
                                ?float $currentPrice = null)
    {
        $this->userId = $userId;
        $this->coinId = $coinId;
        $this->coinName = $coinName;
        $this->coinLogo = $coinLogo;
        $this->amount = $amount;
        $this->id = $id;
        $this->averagePrice = $averagePrice;
        $this->currentPrice = $currentPrice;
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAveragePrice(): float
    {
        return $this->averagePrice;
    }

    public function setAveragePrice(?float $averagePrice): void
    {
        $this->averagePrice = $averagePrice;
    }

    public function getCurrentPrice(): float
    {
        return $this->currentPrice;
    }

    public function setCurrentPrice(?float $currentPrice): void
    {
        $this->currentPrice = $currentPrice;
    }

    public function addAmount(float $amount): void
    {
        $this->amount += $amount;
    }

    public function subtractAmount(float $amount): void
    {
        $this->amount -= $amount;
    }
}
