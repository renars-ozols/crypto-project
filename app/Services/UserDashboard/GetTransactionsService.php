<?php declare(strict_types=1);

namespace App\Services\UserDashboard;

use App\Models\Collections\TransactionCollection;
use App\Repositories\UserDashboard\UserDashboardRepository;

class GetTransactionsService
{
    private UserDashboardRepository $repository;

    public function __construct(UserDashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $userId): ?TransactionCollection
    {
        return $this->repository->getTransactions($userId);
    }
}
