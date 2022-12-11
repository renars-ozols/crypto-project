<?php declare(strict_types=1);

namespace App\Services\UserDashboard;

use App\Repositories\UserDashboard\MySQLUserDashboardRepository;
use App\Repositories\UserDashboard\UserDashboardRepository;

class WithdrawMoneyService
{
    private UserDashboardRepository $userDashboardRepository;

    public function __construct()
    {
        $this->userDashboardRepository = new MySQLUserDashboardRepository();
    }

    public function execute(float $amount): void
    {
        $this->userDashboardRepository->withdrawMoney($amount);
    }
}

