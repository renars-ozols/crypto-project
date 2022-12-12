<?php declare(strict_types=1);

namespace App\Services\BuySellCrypto;

use App\Repositories\BuySellCrypto\BuySellCryptoRepository;

class BuyCryptoService
{
    private BuySellCryptoRepository $repository;

    public function __construct(BuySellCryptoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(BuySellCryptoServiceRequest $request): void
    {
        $this->repository->buyCrypto($request);
    }
}
