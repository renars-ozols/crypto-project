<?php declare(strict_types=1);

namespace App\Services\Money;

use App\Repositories\Users\UserRepository;

class DepositMoneyService
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(float $amount, int $userId): void
    {
        $user = $this->repository->getById($userId);
        $user->addMoney($amount);
        $this->repository->save($user);
    }
}
