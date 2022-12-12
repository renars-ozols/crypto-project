<?php declare(strict_types=1);

namespace App\Models\Collections;

use App\Models\Crypto;

class CryptoCollection
{
    private array $coins;

    public function __construct(array $coins = [])
    {
        foreach ($coins as $coin) {
            $this->add($coin);
        }
    }

    public function add(Crypto $coin): void
    {
        $this->coins[] = $coin;
    }

    public function getCoins(): array
    {
        return $this->coins;
    }
}
