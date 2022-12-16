<?php declare(strict_types=1);

namespace App\Services\UserDashboard;

use App\Models\Collections\TransactionCollection;
use App\Models\Collections\UserCryptoCollection;

class IndexUserDashboardResponse
{
    private UserCryptoCollection $portfolio;
    private TransactionCollection $transactions;

    public function __construct(UserCryptoCollection $portfolio, TransactionCollection $transactions)
    {
        $this->portfolio = $portfolio;
        $this->transactions = $transactions;
    }

    public function getPortfolio(): UserCryptoCollection
    {
        return $this->portfolio;
    }

    public function getTransactions(): TransactionCollection
    {
        return $this->transactions;
    }
}
