<?php declare(strict_types=1);

namespace App\Repositories\Users;

use App\Database;
use App\Models\User;
use App\Services\RegisterServiceRequest;

class MySQLUserRepository implements UserRepository
{
    public function add(RegisterServiceRequest $request): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder->insert('users')
            ->values([
                'name' => '?',
                'email' => '?',
                'password' => '?'
            ])
            ->setParameter(0, $request->getName())
            ->setParameter(1, $request->getEmail())
            ->setParameter(2, password_hash($request->getPassword(), PASSWORD_DEFAULT))
            ->executeQuery();
    }

    public function getById(int $id): User
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $user = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->fetchAssociative();

        return new User(
            (int)$user['id'],
            $user['name'],
            $user['email'],
            $user['password'],
            (float)$user['money']
        );
    }

    public function save(User $user): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder->update('users')
            ->set('money', $user->getBalance())
            ->where('id = ?')
            ->setParameter(0, $user->getId())
            ->executeQuery();
    }
}
