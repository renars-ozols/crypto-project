<?php declare(strict_types=1);

namespace App\Repositories\BuySellCrypto;

use App\Services\BuySellCrypto\BuySellCryptoServiceRequest;

interface BuySellCryptoRepository
{
    public function buyCrypto(BuySellCryptoServiceRequest $request): void;

    public function sellCrypto(BuySellCryptoServiceRequest $request): void;
}
