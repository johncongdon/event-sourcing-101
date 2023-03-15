<?php

namespace ES101\ShoppingCart\Projector;

use Doctrine\DBAL\Connection;
use ES101\ShoppingCart\Event\CartWasInitialized;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;

class UserMapperProjection implements MessageConsumer
{
    public function __construct(private readonly Connection $connection) {

    }

    public function handle(Message $message): void
    {
        $event = $message->payload();
        if (! $event instanceof CartWasInitialized) {
            return;
        }

        $this->connection->executeQuery("INSERT INTO user_cart_map values (:user_id, :shopping_cart_id)", [
            'user_id' => rand(1,999),
            'shopping_cart_id' => $message->aggregateRootId()->toString(),
        ]);
    }
}