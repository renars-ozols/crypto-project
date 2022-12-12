<?php declare(strict_types=1);

namespace App\Models;

class Crypto
{
    private int $id;
    private string $name;
    private string $symbol;
    private string $logo;
    private float $price;
    private float $percentChange24h;
    private float $percentChange1h;
    private float $percentChange7d;

    public function __construct(int $id,
                                string $name,
                                string $symbol,
                                string $logo,
                                float $price,
                                float $percentChange1h,
                                float $percentChange24h,
                                float $percentChange7d)
    {
        $this->id = $id;
        $this->name = $name;
        $this->symbol = $symbol;
        $this->logo = $logo;
        $this->price = $price;
        $this->percentChange24h = $percentChange24h;
        $this->percentChange1h = $percentChange1h;
        $this->percentChange7d = $percentChange7d;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getLogo(): string
    {
        return $this->logo;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getPercentChange1h(): float
    {
        return $this->percentChange1h;
    }

    public function getPercentChange24h(): float
    {
        return $this->percentChange24h;
    }

    public function getPercentChange7d(): float
    {
        return $this->percentChange7d;
    }
}
