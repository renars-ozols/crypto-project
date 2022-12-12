<?php declare(strict_types=1);

namespace App\Services\UserDashboard;

use App\Models\Collections\PortfolioCollection;
use App\Repositories\UserDashboard\UserDashboardRepository;

class GetPortfolioService
{
    private UserDashboardRepository $repository;

    public function __construct(UserDashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $userId): ?PortfolioCollection
    {
        return $this->repository->getPortfolio($userId);
    }
}
