<?php

namespace ES101\ShoppingCart;

use ES101\ShoppingCart\Command\InitializeCart;
use ES101\ShoppingCart\Event\CartWasInitialized;
use ES101\ShoppingCart\Event\ItemWasAdded;
use ES101\ShoppingCart\Event\ItemWasRemoved;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;

class ShoppingCart implements AggregateRoot
{
    use AggregateRootBehaviour {
        reconstituteFromEvents as reconstituteFromEventsTrait;
    }

    private array $items = [];

    private ShoppingCartStatus $status;

    public static function make(ShoppingCartId $aggregate_root_id): self
    {
        $cart = new self($aggregate_root_id);
        $cart->process(new InitializeCart());

        return $cart;
    }

    public function process(Command ...$commands): void
    {
        foreach ($commands as $command) {
            $this->guard($command);
            $events = $command->execute($this);

            foreach ($events as $event) {
                $this->recordThat($event);
            }
        }
    }

    public function applyCartWasInitialized(CartWasInitialized $event): void
    {
        $this->items = [];
        $this->status = ShoppingCartStatus::Shopping;
    }

    public function applyItemWasAdded(ItemWasAdded $event): void
    {
        $this->items[$event->line_item->product->id] = $event->line_item;
    }

    public function applyItemWasRemoved(ItemWasRemoved $event): void
    {
        $product = $event->product();
        unset($this->items[$product->id]);
    }

    public function getItems(): array
    {
        return $this->items;
    }

    private function guard(Command $command)
    {
        $this->assertCartIsInitialized($command);
    }

    private function assertCartIsInitialized(Command $command)
    {
        if ($command instanceof InitializeCart) {
            return;
        }

        if (!isset($this->status)) {
            throw new \InvalidArgumentException('Cart Must be initialized');
        }
    }
}