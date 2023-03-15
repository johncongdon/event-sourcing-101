<?php

require 'vendor/autoload.php';

use Andreo\EventSauce\Snapshotting\Doctrine\DoctrineSnapshotRepository;
use Doctrine\DBAL\DriverManager;
use ES101\CliCommand\ShoppingCartAddItemCommand;
use ES101\CliCommand\ShoppingCartInitCommand;
use ES101\ShoppingCart\ShoppingCart;
use ES101\ShoppingCart\ShoppingCartTableSchema;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\EventSourcing\Snapshotting\ConstructingAggregateRootRepositoryWithSnapshotting;
use EventSauce\MessageRepository\DoctrineMessageRepository\DoctrineUuidV4MessageRepository;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;
use Symfony\Component\Console\Application;

$connectionParams = [
  'dbname' => 'es101-5',
  'user' => 'root',
  'password' => 'root_pw',
  #  'password' => 'pb_pass123',
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
    new \ES101\ShoppingCart\Projector\UserMapperProjection($conn)
);

$aggregateRootRepository = new EventSourcedAggregateRootRepository(
    ShoppingCart::class,
    $messageRepository,
    $messageDispatcher
);

$snapshot_serializer = new \Andreo\EventSauce\Snapshotting\Serializer\ConstructingSnapshotStateSerializer(new \EventSauce\EventSourcing\Serialization\DefaultPayloadSerializer(), new EventSauce\Clock\SystemClock(), new \EventSauce\EventSourcing\ExplicitlyMappedClassNameInflector());

$snapshotRepository = new DoctrineSnapshotRepository(
    connection: $conn, // Doctrine\DBAL\Connection
    tableName: $tableName,
    serializer: new \Andreo\EventSauce\Snapshotting\Serializer\ConstructingSnapshotStateSerializer($payloadSerializer, $clock, $classNameInflector)
    uuidEncoder: $uuidEncoder, // EventSauce\UuidEncoding\UuidEncoder
    tableSchema: $tableSchema // Andreo\EventSauce\Snapshotting\Repository\Table\SnapshotTableSchema
)


$aggregateRepository = new ConstructingAggregateRootRepositoryWithSnapshotting(
    ShoppingCart::class,
    $messageRepository,
    $snapshotRepository,
    $aggregateRootRepository
);


$application = new Application();

$application->add(new ShoppingCartInitCommand($aggregateRootRepository));
$application->add(new ShoppingCartAddItemCommand($aggregateRootRepository, new \ES101\Product\ProductService()));
$application->add(new \ES101\CliCommand\ShoppingCartRemoveItemCommand($aggregateRootRepository));
$application->add(new \ES101\CliCommand\ShoppingCartAdjustItemQtyCommand($aggregateRootRepository));
$application->add(new \ES101\CliCommand\ShoppingCartListItemsCommand($aggregateRootRepository));


$application->run();


