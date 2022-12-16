<?php declare(strict_types=1);

namespace App;

use App\Models\User;
use App\Repositories\Users\MySQLUserRepository;

class Authentication
{
    public static function getAuthId(): ?int
    {
        if (isset($_SESSION['auth_id'])) {
            return (int)$_SESSION['auth_id'];
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
        $userRepository = new MySQLUserRepository();
        return $userRepository->getById(self::getAuthId());
    }
}
