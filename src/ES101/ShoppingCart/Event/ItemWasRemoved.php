<?php

namespace ES101\ShoppingCart\Event;

use ES101\Product\Product;
use ES101\ShoppingCart\Event;

class ItemWasRemoved implements Event
{
    public function __construct(private Product $product)
    {
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function toPayload(): array
    {
        return [
            'product' => serialize($this->product),
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(unserialize($payload['product']));
    }
}