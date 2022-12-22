<?php declare(strict_types=1);

namespace App\Services\ShortCrypto;

use App\Repositories\Crypto\CryptoRepository;
use App\Repositories\ShortSell\ShortSellRepository;
use App\Repositories\Users\UserRepository;

class BuyBackCryptoService
{
    private UserRepository $userRepository;
    private CryptoRepository $cryptoRepository;
    private ShortSellRepository $shortSellRepository;

    public function __construct(UserRepository      $userRepository,
                                CryptoRepository    $cryptoRepository,
                                ShortSellRepository $shortSellRepository)
    {
        $this->userRepository = $userRepository;
        $this->cryptoRepository = $cryptoRepository;
        $this->shortSellRepository = $shortSellRepository;
    }

    public function execute(ShortAndBuyBackServiceRequest $request): void
    {
        $user = $this->userRepository->getById($request->getUserId());
        $coin = $this->cryptoRepository->getCoin($request->getCoinId());
        $shortSellOrder = $this->shortSellRepository->getOpenOrder($user->getId(), $coin->getId());

        $shortSellOrder->reduceQuantity($request->getAmount());
        $shortSellOrder->addTotalRepaid($request->getAmount() * $coin->getPrice());

        if ($shortSellOrder->getQuantity() == 0) {
            $profit = $shortSellOrder->getTotalBorrowed() - $shortSellOrder->getTotalRepaid();
            $shortSellOrder->setProfitLoss($profit);
            $user->addMoney($profit);
            $this->userRepository->save($user);
            $shortSellOrder->closeOrder();
        }

        $this->shortSellRepository->update($shortSellOrder);
    }
}
