<?php

require 'vendor/autoload.php';

use ES101\CliCommand\ShoppingCartAddItemCommand;
use ES101\CliCommand\ShoppingCartInitCommand;
use ES101\ShoppingCart\ShoppingCart;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;
use Symfony\Component\Console\Application;

require 'bootstrap.php';
global $messageRepository;

$messageDispatcher = new SynchronousMessageDispatcher(
);

$aggregateRootRepository = new EventSourcedAggregateRootRepository(
    ShoppingCart::class,
    $messageRepository,
    $messageDispatcher
);

$application = new Application();

$application->add(new ShoppingCartInitCommand($aggregateRootRepository));
$application->add(new ShoppingCartAddItemCommand($aggregateRootRepository, new \ES101\Product\ProductService()));
$application->add(new \ES101\CliCommand\ShoppingCartRemoveItemCommand($aggregateRootRepository));
$application->add(new \ES101\CliCommand\ShoppingCartAdjustItemQtyCommand($aggregateRootRepository));
$application->add(new \ES101\CliCommand\ShoppingCartListItemsCommand($aggregateRootRepository));


$application->run();


