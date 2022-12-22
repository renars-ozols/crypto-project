<?php declare(strict_types=1);

namespace App\Services\ShortCrypto;

use App\Repositories\Crypto\CryptoRepository;
use App\Repositories\ShortSell\ShortSellRepository;
use App\Repositories\Users\UserRepository;

class ShortCryptoService
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

        if ($shortSellOrder && $shortSellOrder->isOpen()) {
            $shortSellOrder->addQuantity($request->getAmount());
            $shortSellOrder->addTotalBorrowed($request->getAmount() * $coin->getPrice());
            $this->shortSellRepository->update($shortSellOrder);
            return;
        }

        $this->shortSellRepository->create(new ShortSellOrderRequest(
            $user->getId(),
            $coin->getId(),
            $coin->getName(),
            $coin->getLogo(),
            $request->getAmount(),
            $coin->getPrice() * $request->getAmount(),
        ));
    }
}
