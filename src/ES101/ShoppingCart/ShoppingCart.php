<?php

namespace ES101\ShoppingCart;

use ES101\ShoppingCart\Event\ItemWasAdded;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;

class ShoppingCart implements AggregateRoot
{
    use AggregateRootBehaviour {
        reconstituteFromEvents as reconstituteFromEventsTrait;
    }

    private array $items = [];

    public static function make(ShoppingCartId $aggregate_root_id): self
    {
        return new self($aggregate_root_id);
    }

    public function process(Command ...$commands): void
    {
        foreach ($commands as $command) {
            $events = $command->execute($this);

            foreach ($events as $event) {
                $this->recordThat($event);
            }
        }
    }

    public function applyItemWasAdded(ItemWasAdded $event) {
        $this->items[] = $event->product;
    }
}