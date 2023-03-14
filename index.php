<?php

require 'vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\EventSourcing\Serialization\ObjectMapperPayloadSerializer;
use EventSauce\MessageRepository\DoctrineMessageRepository\DoctrineUuidV4MessageRepository;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;
use Symfony\Component\Console\Application;

$connectionParams = [
  'dbname' => 'es101',
  'user' => 'root',
  'password' => 'root_pw',
  'host' => '127.0.0.1',
  'driver' => 'pdo_mysql',
];

$conn = DriverManager::getConnection($connectionParams);

$serializar = new ConstructingMessageSerializer(
  payloadSerializer: new ObjectMapperPayloadSerializer()
);

$messageRepository = new DoctrineUuidV4MessageRepository(
  $conn,
  'events',
  $serializar
);

$messageDispatcher = new SynchronousMessageDispatcher(
);

$aggregateRootRepository = new EventSourcedAggregateRootRepository(
    \ES101\ShoppingCart\ShoppingCart::class,
    $messageRepository,
    $messageDispatcher
);

$application = new Application();

$application->add(new \ES101\CliCommand\ShoppingCartInitCommand($aggregateRootRepository));
$application->add(new \ES101\CliCommand\ShoppingCartAddItemCommand($aggregateRootRepository));


$application->run();


