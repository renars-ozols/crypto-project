<?php declare(strict_types=1);

namespace App\Controllers;

use App\Authentication;
use App\Redirect;
use App\Services\BuySellCrypto\BuyCryptoService;
use App\Services\BuySellCrypto\BuySellCryptoServiceRequest;
use App\Services\BuySellCrypto\SellCryptoService;
use App\Validation\Validation;

class BuySellCryptoController
{
    private BuyCryptoService $buyCryptoService;
    private SellCryptoService $sellCryptoService;

    public function __construct(BuyCryptoService $buyCryptoService, SellCryptoService $sellCryptoService)
    {
        $this->buyCryptoService = $buyCryptoService;
        $this->sellCryptoService = $sellCryptoService;
    }

    public function buyCrypto(): Redirect
    {
        //TODO: change price from coming from the form to coming from the database
        //TODO: change that controller method calls only one service method
        $validation = new Validation();
        $validation->validateBuyCryptoForm($_POST['amount'], $_POST['coin_price']);

        if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) {
            return new Redirect('/coin/' . $_POST['coin_id']);
        }

        $this->buyCryptoService->execute(new BuySellCryptoServiceRequest(
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
        //TODO: change price from coming from the form to coming from the database
        //TODO: change that controller method calls only one service method
        //TODO: 1.22 || 2.03!!! || 2.24!!!
        $validation = new Validation();
        $validation->validateSellCryptoForm($_POST['coin_id'], Authentication::getAuthId(), $_POST['amount']);

        if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) {
            return new Redirect('/coin/' . $_POST['coin_id']);
        }

        $this->sellCryptoService->execute(new BuySellCryptoServiceRequest(
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
