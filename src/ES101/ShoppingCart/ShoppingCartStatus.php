<?php

namespace ES101\ShoppingCart;

enum ShoppingCartStatus
{
    case Shopping;
    case Completed;
    case Abandoned;
}