<?php declare(strict_types=1);

namespace App\Services\Crypto;

use App\Repositories\Crypto\CryptoRepository;

class SearchCryptoService
{
    private CryptoRepository $repository;

    public function __construct(CryptoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $query): int
    {
        return $this->repository->searchCoin($query);
    }
}
