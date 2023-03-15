<?php

namespace ES101\Product;

use Money\Currency;
use Money\Money;

class Product
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly Money $price
    ) {
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => json_encode($this->price, JSON_THROW_ON_ERROR),
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->price = Money::USD($data['amount']);
    }
}
