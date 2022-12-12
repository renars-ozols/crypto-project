<?php declare(strict_types=1);

namespace App\Repositories\Money;

interface MoneyRepository
{
    public function deposit(float $amount, string $userId): void;
    public function withdraw(float $amount, string $userId): void;
}
