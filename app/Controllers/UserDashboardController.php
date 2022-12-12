<?php declare(strict_types=1);

namespace App\Controllers;

use App\Authentication;
use App\Services\UserDashboard\GetPortfolioService;
use App\Services\UserDashboard\GetTransactionsService;
use App\Template;

class UserDashboardController
{
    private GetPortfolioService $getPortfolioService;
    private GetTransactionsService $getTransactionsService;

    public function __construct(GetPortfolioService $getPortfolioService,
                                GetTransactionsService $getTransactionsService)
    {
        $this->getPortfolioService = $getPortfolioService;
        $this->getTransactionsService = $getTransactionsService;
    }


    public function index(): Template
    {
        $portfolio = $this->getPortfolioService->execute(Authentication::getAuthId());
        $transactions = $this->getTransactionsService->execute(Authentication::getAuthId());
        return new Template('/authentication/user-dashboard.twig',
            ['portfolio' => $portfolio ? $portfolio->getPortfolio() : [],
            'transactions' =>$transactions ? $transactions->getTransactions() : [] ]);
    }
}
