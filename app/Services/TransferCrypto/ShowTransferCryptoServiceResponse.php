<?php declare(strict_types=1);

namespace App\Services\TransferCrypto;

use App\Models\Collections\UsersCollection;
use App\Models\UserCrypto;

class ShowTransferCryptoServiceResponse
{
    private UsersCollection $users;
    private UserCrypto $userCoin;

    public function __construct(UsersCollection $users, UserCrypto $userCoin)
    {
        $this->users = $users;
        $this->userCoin = $userCoin;
    }

    public function getUsers(): UsersCollection
    {
        return $this->users;
    }

    public function getUserCoin(): UserCrypto
    {
        return $this->userCoin;
    }
}
