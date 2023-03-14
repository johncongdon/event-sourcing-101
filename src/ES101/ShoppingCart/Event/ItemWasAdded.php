<?php

namespace ES101\ShoppingCart\Event;

use ES101\ShoppingCart\Event;
use ES101\ShoppingCart\LineItem;

class ItemWasAdded implements Event
{
    public function __construct(public readonly LineItem $line_item)
    {
    }

    public function toPayload(): array
    {
        return [
            'line_item' => serialize($this->line_item),
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self(unserialize($payload['line_item']));
    }
}