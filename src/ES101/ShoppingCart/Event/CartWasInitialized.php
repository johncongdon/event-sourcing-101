<?php

namespace ES101\ShoppingCart\Event;

use ES101\Product\Product;
use ES101\ShoppingCart\Event;

class CartWasInitialized implements Event
{
    public function toPayload(): array
    {
        return [];
    }

    public static function fromPayload(array $payload): static
    {
        return new self();
    }

    public function serialize()
    {
        return [];
    }

    public function unserialize(string $data)
    {
    }

    public function __serialize(): array
    {
        return [];
    }

    public function __unserialize(array $data): void
    {
    }
}