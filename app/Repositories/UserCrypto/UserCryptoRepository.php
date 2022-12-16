<?php declare(strict_types=1);

namespace App\Repositories\UserCrypto;

use App\Models\Collections\UserCryptoCollection;
use App\Models\UserCrypto;

interface UserCryptoRepository
{
    public function getAll(int $userId): ?UserCryptoCollection;
    public function get(int $userId, int $coinId): ?UserCrypto;
    public function create(UserCrypto $crypto): void;
    public function save(UserCrypto $crypto): void;
    public function delete(int $id): void;
}
