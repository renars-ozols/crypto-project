<?php declare(strict_types=1);

namespace App\Repositories\Users;

use App\Database;
use App\Models\Collections\UsersCollection;
use App\Models\User;
use App\Services\RegisterServiceRequest;

class MySQLUserRepository implements UserRepository
{
    public function getAll(): UsersCollection
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $result = $queryBuilder->select('*')
            ->from('users')
            ->executeQuery()
            ->fetchAllAssociative();

        $users = new UsersCollection();

        foreach ($result as $user) {
            $users->add($this->buildModel($user));
        }
        return $users;
    }

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

    private function buildModel(array $user): User
    {
        return new User(
            (int)$user['id'],
            $user['name'],
            $user['email'],
            $user['password'],
            (float)$user['money']
        );
    }

    public function getById(int $id): ?User
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $user = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->fetchAssociative();

        return $user ? $this->buildModel($user) : null;
    }

    public function getByEmail(string $email): ?User
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $user = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $email)
            ->fetchAssociative();

        return $user ? $this->buildModel($user) : null;
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
