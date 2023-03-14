<?php

namespace ES101\ShoppingCart;

use ES101\ShoppingCart\Event\CartWasInitialized;
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
        $cart = new self($aggregate_root_id);
        $cart->recordThat(new CartWasInitialized());

        return $cart;
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

    public function applyCartWasInitialized(CartWasInitialized $event): void
    {
        $this->items = [];
        $this->status = '';
    }

    public function applyItemWasAdded(ItemWasAdded $event): void
    {
        $this->items[] = $event->product;
    }
}