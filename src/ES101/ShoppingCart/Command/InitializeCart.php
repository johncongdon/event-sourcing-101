<?php

namespace ES101\ShoppingCart\Command;

use ES101\ShoppingCart\Command;
use ES101\ShoppingCart\Event\CartWasInitialized;
use ES101\ShoppingCart\ShoppingCart;

class InitializeCart implements Command
{
    public function execute(ShoppingCart $cart): array
    {
        return [new CartWasInitialized()];
    }
}