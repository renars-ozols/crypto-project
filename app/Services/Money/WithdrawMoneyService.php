<?php declare(strict_types=1);

namespace App\Services\Money;

use App\Repositories\Money\MoneyRepository;

class WithdrawMoneyService
{
    private MoneyRepository $repository;

    public function __construct(MoneyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(float $amount, string $userId): void
    {
        $this->repository->withdraw($amount, $userId);
    }
}

