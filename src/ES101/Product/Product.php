<?php

namespace ES101\Product;

use Money\Currency;
use Money\Money;

class Product
{
    public function __construct(public readonly int $id, public readonly string $item_name, public readonly int $qty, public readonly Money $price)
    {
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->item_name,
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
