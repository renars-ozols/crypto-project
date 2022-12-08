<?php declare(strict_types=1);

namespace App\ViewVariables;

interface ViewVariables
{
    public function getName(): string;

    public function getValue(): array;
}
