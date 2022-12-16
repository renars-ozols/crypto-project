<?php declare(strict_types=1);

namespace App\Services\BuySellCrypto;

use App\Models\Transaction;
use App\Models\TransactionType;
use App\Repositories\Crypto\CryptoRepository;
use App\Repositories\Transactions\TransactionsRepository;
use App\Repositories\UserCrypto\UserCryptoRepository;
use App\Repositories\Users\UserRepository;

class SellCryptoService
{
    private UserRepository $userRepository;
    private CryptoRepository $cryptoRepository;
    private UserCryptoRepository $userCryptoRepository;
    private TransactionsRepository $transactionsRepository;

    public function __construct(UserRepository         $userRepository,
                                CryptoRepository       $cryptoRepository,
                                UserCryptoRepository   $userCryptoRepository,
                                TransactionsRepository $transactionsRepository)
    {
        $this->userRepository = $userRepository;
        $this->cryptoRepository = $cryptoRepository;
        $this->userCryptoRepository = $userCryptoRepository;
        $this->transactionsRepository = $transactionsRepository;
    }

    public function execute(BuySellCryptoServiceRequest $request): void
    {
        $user = $this->userRepository->getById($request->getUserId());
        $coin = $this->cryptoRepository->getCoin($request->getCoinId());

        $user->addMoney($coin->getPrice() * $request->getAmount());
        $this->userRepository->save($user);

        $userCoin = $this->userCryptoRepository->get($request->getUserId(), $request->getCoinId());
        $userCoin->subtractAmount($request->getAmount());
        $this->userCryptoRepository->save($userCoin);

        if ($userCoin->getAmount() == 0) {
            $this->userCryptoRepository->delete($userCoin->getId());
        }

        $this->transactionsRepository->create(
            new Transaction(
                $user->getId(),
                $coin->getId(),
                TransactionType::SELL(),
                $coin->getName(),
                $coin->getPrice(),
                $request->getAmount()
            )
        );
    }
}
