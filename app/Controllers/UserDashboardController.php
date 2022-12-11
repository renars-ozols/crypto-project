<?php declare(strict_types=1);

namespace App\Controllers;

use App\Authentication;
use App\Redirect;
use App\Services\UserDashboard\BuyCryptoService;
use App\Services\UserDashboard\BuySellCryptoServiceRequest;
use App\Services\UserDashboard\DepositMoneyService;
use App\Services\UserDashboard\GetPortfolioService;
use App\Services\UserDashboard\GetTransactionsService;
use App\Services\UserDashboard\SellCryptoService;
use App\Services\UserDashboard\WithdrawMoneyService;
use App\Template;

class UserDashboardController
{
    public function index(): Template
    {
        $portfolio = (new GetPortfolioService())->execute(Authentication::getAuthId());
        $transactions = (new GetTransactionsService())->execute(Authentication::getAuthId());
        return new Template('/authentication/user-dashboard.twig',
            ['portfolio' => $portfolio ? $portfolio->getPortfolio() : [],
            'transactions' =>$transactions ? $transactions->getTransactions() : [] ]);
    }

    public function depositMoney(): Redirect
    {
        $amount = (float) $_POST['amount'];
        (new DepositMoneyService())->execute($amount);
        return new Redirect('/dashboard');
    }

    public function withdrawMoney(): Redirect
    {
        $amount = (float) $_POST['amount'];
        (new WithdrawMoneyService())->execute($amount);
        return new Redirect('/dashboard');
    }

    public function buyCrypto(): Redirect
    {
//        var_dump($_POST);die;
//        $amount = (float) $_POST['amount'];
        (new BuyCryptoService())->execute(new BuySellCryptoServiceRequest(
            Authentication::getAuthId(),
            $_POST['coin_id'],
            $_POST['coin_name'],
            $_POST['coin_logo'],
            $_POST['amount'],
            $_POST['coin_price']
        ));
        return new Redirect('/dashboard');
    }

    public function sellCrypto(): Redirect
    {
//        echo "<pre>";
//        var_dump($_POST);die;
        (new SellCryptoService())->execute(new BuySellCryptoServiceRequest(
            Authentication::getAuthId(),
            $_POST['coin_id'],
            $_POST['coin_name'],
            $_POST['coin_logo'],
            $_POST['amount'],
            $_POST['coin_price']
        ));
        return new Redirect('/dashboard');
    }
}
