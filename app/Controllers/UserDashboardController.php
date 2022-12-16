<?php declare(strict_types=1);

namespace App\Controllers;

use App\Authentication;
use App\Services\UserDashboard\IndexUserDashboardService;
use App\Template;

class UserDashboardController
{

    private IndexUserDashboardService $indexUserDashboardService;

    public function __construct(IndexUserDashboardService $indexUserDashboardService)
    {
        $this->indexUserDashboardService = $indexUserDashboardService;
    }

    public function index(): Template
    {
        $data = $this->indexUserDashboardService->execute(Authentication::getAuthId());
        return new Template('/authentication/user-dashboard.twig',
            ['portfolio' => $data->getPortfolio()->getPortfolio(),
                'transactions' => $data->getTransactions()->getTransactions()
            ]);
    }
}
