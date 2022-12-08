<?php declare(strict_types=1);

namespace App\FormRequests;

class UserRegistrationFormRequest
{
    private string $name;
    private string $email;
    private string $password;
    private string $passwordConfirm;

    public function __construct(string $name, string $email, string $password, string $passwordConfirm)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->passwordConfirm = $passwordConfirm;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPasswordConfirm(): string
    {
        return $this->passwordConfirm;
    }
}
