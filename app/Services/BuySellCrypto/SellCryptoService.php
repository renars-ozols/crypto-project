<?php declare(strict_types=1);

namespace App\Services\BuySellCrypto;

use App\Repositories\BuySellCrypto\BuySellCryptoRepository;

class SellCryptoService
{
    private BuySellCryptoRepository $repository;

    public function __construct(BuySellCryptoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(BuySellCryptoServiceRequest $request): void
    {
        $this->repository->sellCrypto($request);
    }
}
