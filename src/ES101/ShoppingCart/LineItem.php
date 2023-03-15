<?php

namespace ES101\ShoppingCart;

use ES101\Product\Product;
use Money\Money;

class LineItem
{
    public function __construct(public readonly Product $product, public readonly int $qty)
    {
    }
}