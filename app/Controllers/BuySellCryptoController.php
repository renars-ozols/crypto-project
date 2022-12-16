<?php declare(strict_types=1);

namespace App\Controllers;

use App\Authentication;
use App\FormRequests\BuyAndSellCryptoFormRequest;
use App\Redirect;
use App\Services\BuySellCrypto\BuyCryptoService;
use App\Services\BuySellCrypto\BuySellCryptoServiceRequest;
use App\Services\BuySellCrypto\SellCryptoService;
use App\Validation\Validation;

class BuySellCryptoController
{
    private BuyCryptoService $buyCryptoService;
    private SellCryptoService $sellCryptoService;
    private Validation $validation;

    public function __construct(BuyCryptoService $buyCryptoService,
                                SellCryptoService $sellCryptoService,
                                Validation $validation)
    {
        $this->buyCryptoService = $buyCryptoService;
        $this->sellCryptoService = $sellCryptoService;
        $this->validation = $validation;
    }

    public function buyCrypto( array $vars): Redirect
    {
        $this->validation->validateBuyCryptoForm(new BuyAndSellCryptoFormRequest(
            Authentication::getAuthId(),
            (int)$vars['id'],
            (float)$_POST['amount']
        ));

        if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) {
            return new Redirect('/coin/' . $vars['id']);
        }

        $this->buyCryptoService->execute(new BuySellCryptoServiceRequest(
            Authentication::getAuthId(),
            (int)$vars['id'],
            (float)$_POST['amount'],
        ));
        return new Redirect('/dashboard');
    }

    public function sellCrypto(array $vars): Redirect
    {
        $this->validation->validateSellCryptoForm(new BuyAndSellCryptoFormRequest(
            Authentication::getAuthId(),
            (int)$vars['id'],
            (float)$_POST['amount'],
        ));

        if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) {
            return new Redirect('/coin/' . $vars['id']);
        }

        $this->sellCryptoService->execute(new BuySellCryptoServiceRequest(
            Authentication::getAuthId(),
            (int)$vars['id'],
            (float)$_POST['amount'],
        ));
        return new Redirect('/dashboard');
    }
}
