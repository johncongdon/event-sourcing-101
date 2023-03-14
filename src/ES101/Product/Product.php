<?php

namespace ES101\Product;

use Money\Currency;
use Money\Money;

class Product
{
    public const products = [
        1 => ['Magazine', 499],
        2 => ['Book', 1999],
        3 => ['Elephpant', 3000],
        4 => ['php[tek]', 70000],
    ];
    public function __construct(public readonly int $id)
    {
    }

    public function currentPrice(): Money
    {
        return Money::USD(self::products[$this->id][1]);
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
    }
}
