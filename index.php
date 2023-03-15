<?php

require 'vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use ES101\CliCommand\ShoppingCartAddItemCommand;
use ES101\CliCommand\ShoppingCartInitCommand;
use ES101\ShoppingCart\ShoppingCart;
use ES101\ShoppingCart\ShoppingCartTableSchema;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\EventSourcing\Serialization\ObjectMapperPayloadSerializer;
use EventSauce\MessageRepository\DoctrineMessageRepository\DoctrineUuidV4MessageRepository;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;
use EventSauce\MessageRepository\TableSchema\DefaultTableSchema;
use Symfony\Component\Console\Application;

$connectionParams = [
  'dbname' => 'es101',
  'user' => 'root',
  #'password' => 'root_pw',
    'password' => 'pb_pass123',
  'host' => '127.0.0.1',
  'driver' => 'pdo_mysql',
];

$conn = DriverManager::getConnection($connectionParams);

$messageRepository = new DoctrineUuidV4MessageRepository(
  $conn,
  'events',
  new ConstructingMessageSerializer(),
    0,
    new ShoppingCartTableSchema()
);

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


