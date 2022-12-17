<?php declare(strict_types=1);

namespace App\Models\Collections;

use App\Models\User;

class UsersCollection
{
    private array $users;

    public function __construct(array $users = [])
    {
        foreach ($users as $user) {
            $this->add($user);
        }
    }

    public function add(User $user): void
    {
        $this->users[] = $user;
    }

    public function remove(User $user): void
    {
        $this->users = array_filter($this->users, function ($item) use ($user) {
            return $item->getId() !== $user->getId();
        });
    }

    public function getUsers(): array
    {
        return $this->users;
    }
}
