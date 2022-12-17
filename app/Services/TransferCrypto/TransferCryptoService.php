<?php declare(strict_types=1);

namespace App\Services\TransferCrypto;

use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\UserCrypto;
use App\Repositories\Crypto\CryptoRepository;
use App\Repositories\Transactions\TransactionsRepository;
use App\Repositories\UserCrypto\UserCryptoRepository;
use App\Repositories\Users\UserRepository;

class TransferCryptoService
{
    private UserRepository $userRepository;
    private UserCryptoRepository $userCryptoRepository;
    private TransactionsRepository $transactionsRepository;
    private CryptoRepository $cryptoRepository;

    public function __construct(UserRepository         $userRepository,
                                UserCryptoRepository   $userCryptoRepository,
                                TransactionsRepository $transactionsRepository,
                                CryptoRepository       $cryptoRepository)
    {
        $this->userRepository = $userRepository;
        $this->userCryptoRepository = $userCryptoRepository;
        $this->transactionsRepository = $transactionsRepository;
        $this->cryptoRepository = $cryptoRepository;
    }

    public function execute(TransferCryptoServiceRequest $request): void
    {
        $user = $this->userRepository->getById($request->getUserId());
        $recipient = $this->userRepository->getById($request->getRecipientId());
        $userCoin = $this->userCryptoRepository->get($user->getId(), $request->getCoinId());
        $currentPrice = $this->cryptoRepository->getCurrentPrices((string)$userCoin->getCoinId());
        $recipientCoin = $this->userCryptoRepository->get($recipient->getId(), $request->getCoinId());
        $userCoin->subtractAmount($request->getAmount());
        $this->userCryptoRepository->save($userCoin);

        if ($recipientCoin) {
            $recipientCoin->addAmount($request->getAmount());
            $this->userCryptoRepository->save($recipientCoin);
        } else {
            $this->userCryptoRepository->create(
                new UserCrypto(
                    $recipient->getId(),
                    $userCoin->getCoinId(),
                    $userCoin->getCoinName(),
                    $userCoin->getCoinLogo(),
                    $request->getAmount()
                )
            );
        }
        // if sender sent all his coins, delete the coin from his account
        if ($userCoin->getAmount() == 0) {
            $this->userCryptoRepository->delete($userCoin->getId());
        }
        // senders transaction
        $this->transactionsRepository->create(new Transaction(
            $user->getId(),
            $userCoin->getCoinId(),
            TransactionType::TRANSFER(),
            $userCoin->getCoinName(),
            $currentPrice->data->{$userCoin->getCoinId()}->quote->USD->price,
            $request->getAmount(),
        ));
        // recipients transaction
        $this->transactionsRepository->create(new Transaction(
            $recipient->getId(),
            $userCoin->getCoinId(),
            TransactionType::RECEIVED(),
            $userCoin->getCoinName(),
            $currentPrice->data->{$userCoin->getCoinId()}->quote->USD->price,
            $request->getAmount(),
        ));
    }
}