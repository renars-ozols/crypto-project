<?php declare(strict_types=1);

namespace App\Models\Collections;

use App\Models\UserCrypto;

class UserCryptoCollection
{
    private array $portfolio;

    public function __construct(array $portfolio = [])
    {
        foreach ($portfolio as $entry) {
            $this->add($entry);
        }
    }

    public function add(UserCrypto $portfolio): void
    {
        $this->portfolio[] = $portfolio;
    }

    public function getPortfolio(): array
    {
        return $this->portfolio;
    }
}
