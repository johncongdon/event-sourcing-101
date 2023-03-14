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
    public function __construct(public readonly int $id, public readonly string $name, public readonly int $qty, public readonly Money $price)
    {
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'qty' => $this->qty,
            'price' => json_encode($this->price, JSON_THROW_ON_ERROR),
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->qty = $data['qty'];

        $price_data = json_decode($data['price'], true, 512, JSON_THROW_ON_ERROR);
        $this->price = new Money($price_data['amount'], new Currency($price_data['currency']['code']));
    }
}
