<?php

namespace ES101\ShoppingCart\Command;

use ES101\Product\Product;
use ES101\ShoppingCart\Command;
use ES101\ShoppingCart\Event\ItemWasAdded;
use ES101\ShoppingCart\LineItem;
use ES101\ShoppingCart\ShoppingCart;

class AddItem implements Command
{
    public function __construct(public readonly Product $product, public readonly int $qty)
    {
        if ($this->qty <= 0) {
            throw new \InvalidArgumentException('Invalid Qty');
        }
    }

    public function execute(ShoppingCart $cart): array
    {
        $items = $cart->getItems();

        $line_item = new LineItem($this->product, $this->product->currentPrice(), $this->qty);

        if (isset($items[$this->product->id])) {
            $existing_line_item = $items[$this->product->id];
            $qty = $existing_line_item->qty + $this->qty;
            $line_item = new LineItem($existing_line_item->product, $existing_line_item->price, $qty);
        }

        return [
            new ItemWasAdded($line_item),
        ];
    }
}