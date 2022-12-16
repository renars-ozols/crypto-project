<?php declare(strict_types=1);

namespace App\Models;

class User
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private float $money;

    public function __construct(int $id, string $name, string $email, string $password, float $money = 0)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->money = $money;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getBalance(): float
    {
        return $this->money;
    }

    public function deductMoney(float $amount): void
    {
        $this->money -= $amount;
    }

    public function addMoney(float $amount): void
    {
        $this->money += $amount;
    }
}
