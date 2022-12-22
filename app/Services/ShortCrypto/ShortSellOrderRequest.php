<?php declare(strict_types=1);

namespace App\Services\ShortCrypto;

class ShortSellOrderRequest
{
    private int $userId;
    private int $coinId;
    private string $coinName;
    private string $coinLogo;
    private float $quantity;
    private float $totalBorrowed;

    public function __construct(int    $userId,
                                int    $coinId,
                                string $coinName,
                                string $coinLogo,
                                float  $quantity,
                                float  $totalBorrowed)
    {
        $this->userId = $userId;
        $this->coinId = $coinId;
        $this->coinName = $coinName;
        $this->coinLogo = $coinLogo;
        $this->quantity = $quantity;
        $this->totalBorrowed = $totalBorrowed;
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

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function getTotalBorrowed(): float
    {
        return $this->totalBorrowed;
    }
}
