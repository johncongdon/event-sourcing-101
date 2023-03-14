<?php

namespace ES101\ShoppingCart\Event;

use ES101\Product\Product;
use ES101\ShoppingCart\Event;

class ItemWasAdded implements Event
{
    public function __construct(public readonly Product $product)
    {
    }
}