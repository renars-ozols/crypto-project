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
    public function showForm(): Template
    {
        return new Template('/authentication/register.twig');
    }

    public function store(): Redirect
    {
        $validation = new Validation();
        $validation->validateRegistrationForm(new UserRegistrationFormRequest(
            $_POST['name'],
            $_POST['email'],
            $_POST['password'],
            $_POST['passwordConfirm']
        ));

        if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) {
            return new Redirect('/register');
        }

        $registerService = new RegisterService();
        $registerService->execute(new RegisterServiceRequest(
            $_POST['name'],
            $_POST['email'],
            $_POST['password']
        ));

        Authentication::loginByEmail($_POST['email']);

        return new Redirect('/');
    }
}
