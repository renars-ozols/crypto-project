<?php declare(strict_types=1);

namespace App\Controllers;

use App\Authentication;
use App\FormRequests\UserRegistrationFormRequest;
use App\Redirect;
use App\Services\RegisterService;
use App\Services\RegisterServiceRequest;
use App\Template;
use App\Validation\Validation;

class RegisterController
{
    private RegisterService $registerService;
    private Validation $validation;

    public function __construct(RegisterService $registerService, Validation $validation)
    {
        $this->registerService = $registerService;
        $this->validation = $validation;
    }

    public function showForm(): Template
    {
        return new Template('/authentication/register.twig');
    }

    public function store(): Redirect
    {
        $this->validation->validateRegistrationForm(new UserRegistrationFormRequest(
            $_POST['name'],
            $_POST['email'],
            $_POST['password'],
            $_POST['passwordConfirm']
        ));

        if ($this->validation->hasErrors()) {
            return new Redirect('/register');
        }

        $user = $this->registerService->execute(new RegisterServiceRequest(
            $_POST['name'],
            $_POST['email'],
            $_POST['password']
        ));

        if ($user) {
            Authentication::loginById($user);
            return new Redirect('/dashboard');
        }

        return new Redirect('/');
    }
}
