<?php declare(strict_types=1);

namespace App\Controllers;

use App\Authentication;
use App\Redirect;
use App\Services\Money\DepositMoneyService;
use App\Services\Money\WithdrawMoneyService;

class MoneyController
{
    private DepositMoneyService $depositMoneyService;
    private WithdrawMoneyService $withdrawMoneyService;

    public function __construct(DepositMoneyService  $depositMoneyService, WithdrawMoneyService $withdrawMoneyService)
    {
        $this->depositMoneyService = $depositMoneyService;
        $this->withdrawMoneyService = $withdrawMoneyService;
    }

    public function deposit(): Redirect
    {
        $amount = (float)$_POST['amount'];
        $this->depositMoneyService->execute($amount, Authentication::getAuthId());
        return new Redirect('/dashboard');
    }

    public function withdraw(): Redirect
    {
        $amount = (float)$_POST['amount'];
        $this->withdrawMoneyService->execute($amount, Authentication::getAuthId());
        return new Redirect('/dashboard');
    }
}
