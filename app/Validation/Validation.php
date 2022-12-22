<?php declare(strict_types=1);

namespace App\Validation;

use App\FormRequests\CryptoFormRequest;
use App\FormRequests\TransferCryptoFormRequest;
use App\FormRequests\UserLoginFormRequest;
use App\FormRequests\UserRegistrationFormRequest;
use App\Models\User;

class Validation extends Rules
{
    public function hasErrors(): bool
    {
        return isset($_SESSION['errors']) && count($_SESSION['errors']) > 0;
    }

    public function validateRegistrationForm(UserRegistrationFormRequest $request): void
    {
        $this->validateUserName($request->getName());
        $this->validateEmail($request->getEmail());
        $this->validateEmailExists($request->getEmail());
        $this->validatePassword($request->getPassword());
        $this->validateEqualPasswords($request->getPassword(), $request->getPasswordConfirm());
    }

    public function validateLoginForm(UserLoginFormRequest $request): ?User
    {
        $this->validateEmail($request->getEmail());
        $this->validatePassword($request->getPassword());
        $user = $this->validateLoginCredentials($request->getEmail(), $request->getPassword());

        return $user ?: null;
    }

    public function validateMoneyWithdrawalForm(float $amount, int $userId): void
    {
        $this->validateMoneyWithdrawal($amount, $userId);
    }

    public function validateBuyCryptoForm(CryptoFormRequest $request): void
    {
        $this->validateBuyCrypto($request);
    }

    public function validateSellCryptoForm(CryptoFormRequest $request): void
    {
        $this->validateSellCrypto($request);
    }

    public function validateTransferCryptoForm(TransferCryptoFormRequest $request): void
    {
        $this->validateValueGreaterThanZero($request->getAmount());
        $this->validateTransferCrypto($request);
        $this->validateCorrectPassword($request->getPassword(), $request->getUserId());
    }

    public function validateShortSellCryptoForm(CryptoFormRequest $request): void
    {
        $this->validateValueGreaterThanZero($request->getAmount());
        $this->validateBuyCrypto($request, 'short');
    }

    public function validateBuyBackCryptoForm(CryptoFormRequest $request): void
    {
        $this->validateValueGreaterThanZero($request->getAmount());
        $this->validateBuyBackCrypto($request);
    }
}
