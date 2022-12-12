<?php declare(strict_types=1);

namespace App\Repositories\UserDashboard;

use App\Models\Collections\PortfolioCollection;
use App\Models\Collections\TransactionCollection;

interface UserDashboardRepository
{
    public function getPortfolio(string $userId): ?PortfolioCollection;
    public function getTransactions(string $userId): ?TransactionCollection;
}
