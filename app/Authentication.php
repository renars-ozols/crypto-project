<?php declare(strict_types=1);

namespace App;

use App\Models\User;

class Authentication
{
    public static function getAuthId(): ?string
    {
        if (isset($_SESSION['auth_id'])) {
            return $_SESSION['auth_id'];
        }
        return null;
    }

    public static function loginById(User $user): void
    {
        $_SESSION['auth_id'] = $user->getId();
    }

    public static function loginByEmail(string $email): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $user = $queryBuilder
            ->select('id')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $email)
            ->fetchAssociative();
        if ($user) {
            $_SESSION['auth_id'] = $user['id'];
        }
    }

    public static function getUser(): User
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $user = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, self::getAuthId())
            ->fetchAssociative();

        return new User(
            $user['id'],
            $user['name'],
            $user['email'],
            $user['password']
        );
    }
}
