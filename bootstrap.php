<?php

use Doctrine\DBAL\DriverManager;
use ES101\ShoppingCart\ShoppingCartTableSchema;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\MessageRepository\DoctrineMessageRepository\DoctrineUuidV4MessageRepository;

$connectionParams = [
    'dbname' => 'es101',
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

