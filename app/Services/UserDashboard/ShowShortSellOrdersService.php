<?php declare(strict_types=1);

namespace App\Services\UserDashboard;

use App\Repositories\Crypto\CryptoRepository;
use App\Repositories\ShortSell\ShortSellRepository;
use App\Repositories\Users\UserRepository;

class ShowShortSellOrdersService
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

    public function execute(int $userId): ShowShortSellOrdersResponse
    {
        $user = $this->userRepository->getById($userId);
        $openShortSellOrders = $this->shortSellRepository->getAllOpenShortSellOrders($user->getId());
        $closedShortSellOrders = $this->shortSellRepository->getAllClosedShortSellOrders($user->getId());

        if ($openShortSellOrders) {
            $queryIds = [];
            foreach ($openShortSellOrders->getAll() as $order) {
                $queryIds[] = $order->getCoinId();
            }

            $currentPrices = $this->cryptoRepository->getCurrentPrices(implode(',', $queryIds));

            foreach ($openShortSellOrders->getAll() as $order) {
                $order->setCurrentPrice($currentPrices->data->{$order->getCoinId()}->quote->USD->price);
                $order->setProfitLoss(($order->getTotalBorrowed() - $order->getTotalRepaid()) - ($order->getQuantity() * $order->getCurrentPrice()));
            }
        }

        return new ShowShortSellOrdersResponse($openShortSellOrders, $closedShortSellOrders);
    }
}
