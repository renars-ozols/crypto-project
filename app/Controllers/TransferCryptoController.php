<?php declare(strict_types=1);

namespace App\Controllers;

use App\Authentication;
use App\FormRequests\TransferCryptoFormRequest;
use App\Redirect;
use App\Services\TransferCrypto\ShowTransferCryptoService;
use App\Services\TransferCrypto\TransferCryptoService;
use App\Services\TransferCrypto\TransferCryptoServiceRequest;
use App\Template;
use App\Validation\Validation;

class TransferCryptoController
{
    private ShowTransferCryptoService $showTransferCryptoService;
    private TransferCryptoService $transferCryptoService;
    private Validation $validation;

    public function __construct(ShowTransferCryptoService $showTransferCryptoService,
                                TransferCryptoService     $transferCryptoService,
                                Validation                $validation)
    {
        $this->showTransferCryptoService = $showTransferCryptoService;
        $this->transferCryptoService = $transferCryptoService;
        $this->validation = $validation;
    }

    public function show(array $vars): Template
    {
        $data = $this->showTransferCryptoService->execute(Authentication::getAuthId(), (int)$vars['id']);
        return new Template('crypto/transfer.twig', [
            'users' => $data->getUsers()->getUsers(),
            'coin' => $data->getUserCoin()
        ]);
    }

    public function transfer(array $vars): Redirect
    {
        $this->validation->validateTransferCryptoForm(new TransferCryptoFormRequest(
            Authentication::getAuthId(),
            (int)$_POST['recipient_id'],
            (int)$vars['id'],
            (float)$_POST['amount'],
            $_POST['password']));

        if ($this->validation->hasErrors()) {
            return new Redirect('/coin/' . $vars['id'] . '/transfer');
        }

        $this->transferCryptoService->execute(new TransferCryptoServiceRequest(
            Authentication::getAuthId(),
            (int)$_POST['recipient_id'],
            (int)$vars['id'],
            (float)$_POST['amount'],
        ));

        return new Redirect('/dashboard');
    }
}
