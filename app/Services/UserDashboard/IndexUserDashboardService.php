<?php declare(strict_types=1);

namespace App\Services\UserDashboard;

use App\Repositories\Crypto\CryptoRepository;
use App\Repositories\Transactions\TransactionsRepository;
use App\Repositories\UserCrypto\UserCryptoRepository;
use App\Repositories\Users\UserRepository;

class IndexUserDashboardService
{
    private UserRepository $userRepository;
    private TransactionsRepository $transactionsRepository;
    private UserCryptoRepository $userCryptoRepository;
    private CryptoRepository $cryptoRepository;

    public function __construct(UserRepository         $userRepository,
                                TransactionsRepository $transactionsRepository,
                                UserCryptoRepository   $userCryptoRepository,
                                CryptoRepository       $cryptoRepository)
    {
        $this->userRepository = $userRepository;
        $this->transactionsRepository = $transactionsRepository;
        $this->userCryptoRepository = $userCryptoRepository;
        $this->cryptoRepository = $cryptoRepository;
    }

    public function execute(int $userId): IndexUserDashboardResponse
    {
        $user = $this->userRepository->getById($userId);
        $portfolio = $this->userCryptoRepository->getAll($user->getId());
        $transactions = $this->transactionsRepository->getAll($user->getId());

        if ($portfolio) {
            $queryIds = [];
            foreach ($portfolio->getPortfolio() as $coin) {
                $queryIds[] = $coin->getCoinId();
            }

            $averagePrices = $this->transactionsRepository->getAverageBuyingPrices($user->getId());
            $currentPrices = $this->cryptoRepository->getCurrentPrices(implode(',', $queryIds));
            foreach ($portfolio->getPortfolio() as $coin) {
                $coin->setCurrentPrice($currentPrices->data->{$coin->getCoinId()}->quote->USD->price);
                $coin->setAveragePrice(
                    array_key_exists($coin->getCoinId(), $averagePrices)
                        ? (float)$averagePrices[$coin->getCoinId()]
                        : 0
                );
            }

        }
        return new IndexUserDashboardResponse($transactions, $portfolio);
    }
}
