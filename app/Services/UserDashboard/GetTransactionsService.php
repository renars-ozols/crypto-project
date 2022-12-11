<?php declare(strict_types=1);

namespace App\Services\UserDashboard;

use App\Models\Collections\TransactionCollection;
use App\Repositories\UserDashboard\MySQLUserDashboardRepository;
use App\Repositories\UserDashboard\UserDashboardRepository;

class GetTransactionsService
{
    private UserDashboardRepository $userDashboardRepository;

    public function __construct()
    {
        $this->userDashboardRepository = new MySQLUserDashboardRepository();
    }

    public function execute(string $userId): ?TransactionCollection
    {
        return $this->userDashboardRepository->getTransactions($userId);
    }
}
