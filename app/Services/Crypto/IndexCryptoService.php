<?php declare(strict_types=1);

namespace App\Services\Crypto;

use App\Models\Collections\CryptoCollection;
use App\Repositories\Crypto\CryptoRepository;

class IndexCryptoService
{
    private CryptoRepository $repository;

    public function __construct(CryptoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $limit): CryptoCollection
    {
        return $this->repository->getCoins($limit);
    }
}
