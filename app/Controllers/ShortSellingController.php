<?php declare(strict_types=1);

namespace App\Controllers;

use App\Authentication;
use App\FormRequests\CryptoFormRequest;
use App\Redirect;
use App\Services\ShortCrypto\BuyBackCryptoService;
use App\Services\ShortCrypto\ShortAndBuyBackServiceRequest;
use App\Services\ShortCrypto\ShortCryptoService;
use App\Validation\Validation;

class ShortSellingController
{
    private ShortCryptoService $shortCryptoService;
    private BuyBackCryptoService $buyBackCryptoService;
    private Validation $validation;

    public function __construct(ShortCryptoService   $shortCryptoService,
                                BuyBackCryptoService $buyBackCryptoService,
                                Validation           $validation)
    {
        $this->shortCryptoService = $shortCryptoService;
        $this->buyBackCryptoService = $buyBackCryptoService;
        $this->validation = $validation;
    }

    public function shortCrypto(array $vars): Redirect
    {
        $this->validation->validateShortSellCryptoForm(new CryptoFormRequest(
            Authentication::getAuthId(),
            (int)$vars['id'],
            (float)$_POST['amount'],
        ));

        if ($this->validation->hasErrors()) {
            return new Redirect('/coin/' . $vars['id']);
        }

        $this->shortCryptoService->execute(new ShortAndBuyBackServiceRequest(
            Authentication::getAuthId(),
            (int)$vars['id'],
            (float)$_POST['amount'],
        ));
        return new Redirect('/dashboard/short-sell-orders');
    }

    public function buyBackCrypto(array $vars): Redirect
    {
        $this->validation->validateBuyBackCryptoForm(new CryptoFormRequest(
            Authentication::getAuthId(),
            (int)$vars['id'],
            (float)$_POST['amount'],
        ));

        if ($this->validation->hasErrors()) {
            return new Redirect('/coin/' . $vars['id']);
        }

        $this->buyBackCryptoService->execute(new ShortAndBuyBackServiceRequest(
            Authentication::getAuthId(),
            (int)$vars['id'],
            (float)$_POST['amount'],
        ));
        return new Redirect('/dashboard/short-sell-orders');
    }
}
