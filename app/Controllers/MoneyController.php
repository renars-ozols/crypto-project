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
    private Validation $validation;

    public function __construct(DepositMoneyService  $depositMoneyService,
                                WithdrawMoneyService $withdrawMoneyService,
                                Validation           $validation)
    {
        $this->depositMoneyService = $depositMoneyService;
        $this->withdrawMoneyService = $withdrawMoneyService;
        $this->validation = $validation;
    }

    public function deposit(): Redirect
    {
        $this->depositMoneyService->execute((float)$_POST['amount'], Authentication::getAuthId());
        return new Redirect('/dashboard');
    }

    public function withdraw(): Redirect
    {
        $amount = (float)$_POST['amount'];

        $this->validation->validateMoneyWithdrawalForm($amount, Authentication::getAuthId());

        if ($this->validation->hasErrors()) {
            return new Redirect('/dashboard');
        }

        $this->withdrawMoneyService->execute($amount, Authentication::getAuthId());
        return new Redirect('/dashboard');
    }
}
