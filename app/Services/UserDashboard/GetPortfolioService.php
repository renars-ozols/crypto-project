<?php declare(strict_types=1);

namespace App\Services\UserDashboard;

use App\Models\Collections\PortfolioCollection;
use App\Repositories\UserDashboard\MySQLUserDashboardRepository;
use App\Repositories\UserDashboard\UserDashboardRepository;

class GetPortfolioService
{
    private UserDashboardRepository $userDashboardRepository;

    public function __construct()
    {
        $this->userDashboardRepository = new MySQLUserDashboardRepository();
    }

    public function execute(string $userId): ?PortfolioCollection
    {
        return $this->userDashboardRepository->getPortfolio($userId);
    }
}
