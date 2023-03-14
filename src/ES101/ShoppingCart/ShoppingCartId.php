<?php

namespace ES101\ShoppingCart;

use EventSauce\EventSourcing\AggregateRootId;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ShoppingCartId implements AggregateRootId
{
    private UuidInterface $uuid;

    public function __construct(string $uuid = null)
    {
        if ($uuid === null) {
            $this->uuid = Uuid::uuid4();
            return;
        }

        $this->uuid = Uuid::fromString($uuid);
    }

    public function toString(): string
    {
        return (string) $this->uuid;
    }

    public static function fromString(string $aggregateRootId): static
    {
        return new self($aggregateRootId);
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}