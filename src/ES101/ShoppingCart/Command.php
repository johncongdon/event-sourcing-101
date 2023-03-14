<?php

namespace ES101\ShoppingCart;

interface Command
{
    /**
     * @return array<Event>
     */
    public function execute(ShoppingCart $cart): array;
}