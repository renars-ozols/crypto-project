<?php declare(strict_types=1);

namespace App\Services\Crypto;

use App\Repositories\Crypto\CryptoRepository;
use Exception;

class SearchCryptoService
{
    private CryptoRepository $repository;

    public function __construct(CryptoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $query): ?int
    {
        try {
            return $this->repository->getCoinBySymbol($query)->getId();
        } catch (Exception $e) {
            return null;
        }
    }
}
