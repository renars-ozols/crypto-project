<?php declare(strict_types=1);

namespace App\Controllers;

use App\Authentication;
use App\Redirect;
use App\Services\Money\DepositMoneyService;
use App\Services\Money\WithdrawMoneyService;
use App\Validation\Validation;

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

        $validation = new Validation();
        $validation->validateMoneyWithdrawalForm($amount);

        if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) {
            return new Redirect('/dashboard');
        }

        $this->withdrawMoneyService->execute($amount, Authentication::getAuthId());
        return new Redirect('/dashboard');
    }
}
