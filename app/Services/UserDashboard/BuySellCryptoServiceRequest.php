<?php declare(strict_types=1);

namespace App\Services\UserDashboard;

class BuySellCryptoServiceRequest
{
    private string $userId;
    private string $coinId;
    private string $coinName;
    private string $coinLogo;
    private string $amount;
    private string $coinPrice;

    public function __construct(string $userId,
                                string $coinId,
                                string $coinName,
                                string $coinLogo,
                                string $amount,
                                string $coinPrice)
    {
        $this->userId = $userId;
        $this->coinId = $coinId;
        $this->coinName = $coinName;
        $this->coinLogo = $coinLogo;
        $this->amount = $amount;
        $this->coinPrice = $coinPrice;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getCoinId(): string
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

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCoinPrice(): string
    {
        return $this->coinPrice;
    }
}
