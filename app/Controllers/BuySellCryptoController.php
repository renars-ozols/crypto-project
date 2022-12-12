<?php declare(strict_types=1);

namespace App\Controllers;

use App\Authentication;
use App\Redirect;
use App\Services\BuySellCrypto\BuyCryptoService;
use App\Services\BuySellCrypto\BuySellCryptoServiceRequest;
use App\Services\BuySellCrypto\SellCryptoService;

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
