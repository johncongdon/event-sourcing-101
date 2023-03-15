<?php

namespace ES101\ShoppingCart\Projector;

use Doctrine\DBAL\Connection;
use ES101\ShoppingCart\Event\ItemWasAdded;
use ES101\ShoppingCart\Event\ItemWasRemoved;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;

class LifetimeQtyProjector implements MessageConsumer
{
    public function __construct(protected readonly Connection $conn)
    {

    }
    public function handle(Message $message): void
    {
        $event = $message->payload();

        if ($event instanceof ItemWasAdded) {
            $this->conn->executequery("insert into qty_lifetime_sold VALUES (:product_id, :qty) ON DUPLICATE KEY UPDATE qty = :qty", [
                'product_id' => $event->line_item->product->id,
                'qty' => (int)$event->line_item->qty,
            ]);
        }

        if ($event instanceof ItemWasRemoved) {
            $this->conn->executequery("insert into qty_lifetime_sold VALUES (:product_id, :qty) ON DUPLICATE KEY UPDATE qty=:qty", [
                'product_id' => $event->line_item->product->id,
                'qty' => (int)$event->line_item->qty,
            ]);
        }
    }
}