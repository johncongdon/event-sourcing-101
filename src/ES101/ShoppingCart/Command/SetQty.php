<?php

namespace ES101\ShoppingCart\Command;

use ES101\Product\ProductService;
use ES101\ShoppingCart\Command;
use ES101\ShoppingCart\Event\ItemWasAdded;
use ES101\ShoppingCart\Event\ItemWasRemoved;
use ES101\ShoppingCart\LineItem;
use ES101\ShoppingCart\ShoppingCart;

class SetQty implements Command
{
    public function __construct(public readonly int $product_id, public readonly int $qty)
    {
        if ($this->qty < 0) {
            throw new \InvalidArgumentException('Qty must by >= 0');
        }
    }

    public function execute(ShoppingCart $cart): array
    {
        $items = $cart->getItems();
        if ( ! isset($items[$this->product_id])) {
            throw new \InvalidArgumentException('Item does not exist');
        }

        /** @var \ES101\ShoppingCart\LineItem $cart_item */
        $cart_item = $items[$this->product_id];
        if ($this->qty === 0) {
            return [
                new ItemWasRemoved(new ProductService($this->product_id)),
            ];
        }

        return [
            new ItemWasAdded(new LineItem($cart_item->product, $cart_item->price, $this->qty)),
        ];
    }
}