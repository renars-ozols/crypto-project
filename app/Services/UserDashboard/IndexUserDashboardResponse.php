<?php declare(strict_types=1);

namespace App\Services\UserDashboard;

use App\Models\Collections\TransactionCollection;
use App\Models\Collections\UserCryptoCollection;

class IndexUserDashboardResponse
{
    private ?TransactionCollection $transactions;
    private ?UserCryptoCollection $portfolio;

    public function __construct(?TransactionCollection $transactions = null, ?UserCryptoCollection $portfolio = null)
    {
        $this->transactions = $transactions;
        $this->portfolio = $portfolio;
    }

    public function getTransactions(): ?TransactionCollection
    {
        return $this->transactions;
    }

    public function getPortfolio(): ?UserCryptoCollection
    {
        return $this->portfolio;
    }
}
