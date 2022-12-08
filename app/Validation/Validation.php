<?php declare(strict_types=1);

namespace App\Validation;

use App\FormRequests\UserLoginFormRequest;
use App\FormRequests\UserRegistrationFormRequest;
use App\Models\User;

class Validation extends Rules
{
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
}
