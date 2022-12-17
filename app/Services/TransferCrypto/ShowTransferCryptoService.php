<?php declare(strict_types=1);

namespace App\Services\TransferCrypto;

use App\Repositories\UserCrypto\UserCryptoRepository;
use App\Repositories\Users\UserRepository;

class ShowTransferCryptoService
{
    private UserRepository $userRepository;
    private UserCryptoRepository $userCryptoRepository;

    public function __construct(UserRepository       $userRepository,
                                UserCryptoRepository $userCryptoRepository)
    {
        $this->userRepository = $userRepository;
        $this->userCryptoRepository = $userCryptoRepository;
    }

    public function execute(int $userId, int $coinId): ShowTransferCryptoServiceResponse
    {
        $user = $this->userRepository->getById($userId);
        $userCoin = $this->userCryptoRepository->get($user->getId(), $coinId);
        $users = $this->userRepository->getAll();
        $users->remove($user);
        return new ShowTransferCryptoServiceResponse($users, $userCoin);
    }
}
