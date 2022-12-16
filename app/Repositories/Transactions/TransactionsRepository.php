<?php declare(strict_types=1);

namespace App\Repositories\Transactions;

use App\Models\Collections\TransactionCollection;
use App\Models\Transaction;

interface TransactionsRepository
{
    public function getAll(int $userId): ?TransactionCollection;

//    public function get(int $id): Transaction;
    public function create(Transaction $transaction): void;
//    public function save(Transaction $transaction): void;
//    public function delete(int $id): void;
    public function getAverageBuyingPrices(int $userId): array;
}
