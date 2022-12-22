<?php declare(strict_types=1);

namespace App\Controllers;

use App\Authentication;
use App\Services\UserDashboard\IndexUserDashboardService;
use App\Services\UserDashboard\ShowShortSellOrdersService;
use App\Template;

class UserDashboardController
{
    private IndexUserDashboardService $indexUserDashboardService;
    private ShowShortSellOrdersService $showShortSellOrdersService;

    public function __construct(IndexUserDashboardService  $indexUserDashboardService,
                                ShowShortSellOrdersService $showShortSellOrdersService)
    {
        $this->indexUserDashboardService = $indexUserDashboardService;
        $this->showShortSellOrdersService = $showShortSellOrdersService;
    }

    public function index(): Template
    {
        $data = $this->indexUserDashboardService->execute(Authentication::getAuthId());
        return new Template('/dashboard/user-dashboard.twig',
            ['portfolio' => $data->getPortfolio() ? $data->getPortfolio()->getPortfolio() : [],
                'transactions' => $data->getTransactions() ? $data->getTransactions()->getTransactions() : []
            ]);
    }

    public function showShortSellOrders(): Template
    {
        $data = $this->showShortSellOrdersService->execute(Authentication::getAuthId());
        return new Template('/dashboard/short-sell-orders.twig', [
            'openOrders' => $data->getOpenShortSellOrders() ? $data->getOpenShortSellOrders()->getAll() : [],
            'closedOrders' => $data->getClosedShortSellOrders() ? $data->getClosedShortSellOrders()->getAll() : []
        ]);
    }
}
