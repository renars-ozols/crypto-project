<?php declare(strict_types=1);

namespace App\ViewVariables;

class InputValueViewVariables implements ViewVariables
{
    public function getName(): string
    {
        return 'input';
    }

    public function getValue(): array
    {
        return [
            'buyback' => $_GET['buyback'] ?? '',
            'sell' => $_GET['sell'] ?? '',
        ];
    }
}
