<?php declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;

class ShortSellOrder
{
    //TODO: remove nullables
    private int $userId;
    private int $coinId;
    private string $coinName;
    private string $coinLogo;
    private float $quantity;
    private float $currentPrice = 0;
    private float $totalBorrowed;
    private float $totalRepaid;
    private float $profitLoss;
    private ShortSellOrderType $status;
    private ?int $id;
    private ?string $updatedAt;
    private ?string $closedAt;
    private ?string $createdAt;

    public function __construct(int                $userId,
                                int                $coinId,
                                string             $coinName,
                                string             $coinLogo,
                                float              $quantity,
                                float              $totalBorrowed,
                                float              $totalRepaid,
                                float              $profitLoss,
                                ShortSellOrderType $status,
                                ?int               $id = null,
                                ?string            $updatedAt = null,
                                ?string            $closedAt = null,
                                ?string            $createdAt = null)
    {
        $this->userId = $userId;
        $this->coinId = $coinId;
        $this->coinName = $coinName;
        $this->coinLogo = $coinLogo;
        $this->quantity = $quantity;
        $this->totalBorrowed = $totalBorrowed;
        $this->totalRepaid = $totalRepaid;
        $this->profitLoss = $profitLoss;
        $this->status = $status;
        $this->id = $id;
        $this->updatedAt = $updatedAt;
        $this->closedAt = $closedAt;
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

    public function getCurrentPrice(): float
    {
        return $this->currentPrice;
    }

    public function setCurrentPrice(float $currentPrice): void
    {
        $this->currentPrice = $currentPrice;
    }

    public function getTotalBorrowed(): float
    {
        return $this->totalBorrowed;
    }

    public function getTotalRepaid(): float
    {
        return $this->totalRepaid;
    }

    public function getProfitLoss(): float
    {
        return $this->profitLoss;
    }

    public function setProfitLoss(float $profitLoss): void
    {
        $this->profitLoss = $profitLoss;
    }

    public function getStatus(): ShortSellOrderType
    {
        return $this->status;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function getClosedAt(): ?string
    {
        return $this->closedAt;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function addQuantity(float $amount): void
    {
        $this->quantity += $amount;
    }

    public function reduceQuantity(float $amount): void
    {
        $this->quantity -= $amount;
    }

    public function isOpen(): bool
    {
        return $this->status == ShortSellOrderType::OPEN()->getValue();
    }

    public function closeOrder(): void
    {
        //TODO: set carbon timezone in index.php
        $this->status = ShortSellOrderType::CLOSED();
        $this->closedAt = Carbon::now('Europe/Riga')->toDateTimeString();
    }

    public function addTotalBorrowed(float $amount): void
    {
        $this->totalBorrowed += $amount;
    }

    public function addTotalRepaid(float $amount): void
    {
        $this->totalRepaid += $amount;
    }
}
