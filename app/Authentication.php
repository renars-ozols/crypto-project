<?php declare(strict_types=1);

namespace App;

use App\Models\User;

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
}
