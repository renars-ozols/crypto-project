<?php declare(strict_types=1);

namespace App\Services\BuySellCrypto;

use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\UserCrypto;
use App\Repositories\Crypto\CryptoRepository;
use App\Repositories\Transactions\TransactionsRepository;
use App\Repositories\UserCrypto\UserCryptoRepository;
use App\Repositories\Users\UserRepository;

class BuyCryptoService
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

        $user->deductMoney($coin->getPrice() * $request->getAmount());
        $this->userRepository->save($user);

        $userCoin = $this->userCryptoRepository->get($request->getUserId(), $request->getCoinId());

        if ($userCoin) {
            $userCoin->addAmount($request->getAmount());
            $this->userCryptoRepository->save($userCoin);
        } else {
            $this->userCryptoRepository->create(
                new UserCrypto(
                    $user->getId(),
                    $coin->getId(),
                    $coin->getName(),
                    $coin->getLogo(),
                    $request->getAmount()
                )
            );
        }

        $this->transactionsRepository->create(
            new Transaction(
                $user->getId(),
                $coin->getId(),
                TransactionType::BUY(),
                $coin->getName(),
                $coin->getPrice(),
                $request->getAmount()
            )
        );
    }
}
