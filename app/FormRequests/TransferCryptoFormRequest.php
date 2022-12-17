<?php declare(strict_types=1);

namespace App\FormRequests;

class TransferCryptoFormRequest
{
    private int $userId;
    private int $recipientId;
    private int $coinId;
    private float $amount;
    private string $password;

    public function __construct(int $userId, int $recipientId, int $coinId, float $amount, string $password)
    {
        $this->userId = $userId;
        $this->recipientId = $recipientId;
        $this->coinId = $coinId;
        $this->amount = $amount;
        $this->password = $password;
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

    public function getPassword(): string
    {
        return $this->password;
    }
}
