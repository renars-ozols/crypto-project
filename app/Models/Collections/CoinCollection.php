<?php declare(strict_types=1);

namespace App\Models\Collections;

use App\Models\Coin;

class CoinCollection
{
    private array $coins;

    public function __construct(array $coins = [])
    {
        foreach ($coins as $coin) {
            $this->add($coin);
        }
    }

    public function add(Coin $coin): void
    {
        $this->coins[] = $coin;
    }

    public function getCoins(): array
    {
        return $this->coins;
    }
}
