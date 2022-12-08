<?php declare(strict_types=1);

namespace App\Validation;

use App\Authentication;
use App\Database;
use App\Models\User;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as validator;

class Rules
{
    protected function validateUserName(string $name): void
    {
        $userNameValidator = validator::alpha()->length(3, 15);
        try {
            $userNameValidator->check($name);
        } catch (ValidationException $exception) {
            $this->addError('name', $exception->getMessage());
        }
    }

    protected function validateEmail(string $email): void
    {
        $emailValidator = validator::email();
        try {
            $emailValidator->check($email);
        } catch (ValidationException $exception) {
            $this->addError('email', $exception->getMessage());
        }
    }

    protected function validateEmailExists(string $email): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $checkIfEmailExists = $queryBuilder
            ->select('email')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $email)
            ->fetchAssociative();

        if ($checkIfEmailExists) {
            $this->addError('email', 'this email is already taken!');
        }
    }

    protected function validatePassword(string $password, string $errorName = 'password'): void
    {
        $passwordValidator = validator::alnum()->length(5);
        try {
            $passwordValidator->check($password);
        } catch (ValidationException $exception) {
            $this->addError($errorName, $exception->getMessage());
        }
    }

    protected function validateEqualPasswords(string $firstPassword, string $secondPassword): void
    {
        $equalPasswordValidator = validator::identical($firstPassword);
        try {
            $equalPasswordValidator->check($secondPassword);
        } catch (ValidationException $exception) {
            $this->addError('passwordConfirm', $exception->getMessage());
        }
    }

    protected function validateCurrentPassword(string $password): void
    {
        if (!password_verify($password, Authentication::getUser()->getPassword())) {
            $this->addError('password', 'wrong password!');
        }
    }

    protected function validateLoginCredentials(string $email, string $password): ?User
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $user = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $email)->fetchAssociative();
        if ($user) {
            $validPassword = password_verify($password, $user['password']);
            if ($validPassword) {
                return new User(
                    $user['id'],
                    $user['name'],
                    $user['email'],
                    $user['password']
                );
            }
        }
        $this->addError('email', 'wrong email or password');
        return null;
    }

    private function addError(string $name, string $message): void
    {
        $_SESSION['errors'][$name] = $message;
    }
}
