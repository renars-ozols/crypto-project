<?php declare(strict_types=1);

namespace App\Models;

class Coin
{
    private int $id;
    private string $name;
    private string $symbol;
    private string $logo;
    private float $price;
    private float $percentChange24h;

    public function __construct(int $id,
                                string $name,
                                string $symbol,
                                string $logo,
                                float $price,
                                float $percentChange24h)
    {
        $this->id = $id;
        $this->name = $name;
        $this->symbol = $symbol;
        $this->logo = $logo;
        $this->price = $price;
        $this->percentChange24h = $percentChange24h;
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

    public function getPercentChange24h(): float
    {
        return $this->percentChange24h;
    }
}
