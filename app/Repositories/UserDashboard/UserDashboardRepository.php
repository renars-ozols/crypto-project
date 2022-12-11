<?php declare(strict_types=1);

namespace App\Repositories\UserDashboard;

use App\Models\Collections\PortfolioCollection;
use App\Models\Collections\TransactionCollection;
use App\Services\UserDashboard\BuySellCryptoServiceRequest;

interface UserDashboardRepository
{
    public function depositMoney(float $amount): void;
    public function withdrawMoney(float $amount): void;
    public function buyCrypto(BuySellCryptoServiceRequest $request): void;
    public function sellCrypto(BuySellCryptoServiceRequest $request): void;
    public function getPortfolio(string $userId): ?PortfolioCollection;
    public function getTransactions(string $userId): ?TransactionCollection;
}
