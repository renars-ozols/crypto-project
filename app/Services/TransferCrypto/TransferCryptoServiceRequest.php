<?php declare(strict_types=1);

namespace App\Services\TransferCrypto;

class TransferCryptoServiceRequest
{
    private int $userId;
    private int $recipientId;
    private int $coinId;
    private float $amount;

    public function __construct(int $userId, int $recipientId, int $coinId, float $amount)
    {
        $this->userId = $userId;
        $this->recipientId = $recipientId;
        $this->coinId = $coinId;
        $this->amount = $amount;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getRecipientId(): int
    {
        return $this->recipientId;
    }

    public function getCoinId(): int
    {
        return $this->coinId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
