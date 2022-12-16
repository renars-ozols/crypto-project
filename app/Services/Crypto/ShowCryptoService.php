<?php declare(strict_types=1);

namespace App\Services\Crypto;

use App\Models\Crypto;
use App\Repositories\Crypto\CryptoRepository;

class ShowCryptoService
{
    private CryptoRepository $repository;

    public function __construct(CryptoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id): Crypto
    {
        return $this->repository->getCoin($id);
    }
}