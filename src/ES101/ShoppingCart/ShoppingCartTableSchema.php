<?php

namespace ES101\ShoppingCart;

use EventSauce\MessageRepository\TableSchema\TableSchema;

class ShoppingCartTableSchema implements TableSchema
{
    public function incrementalIdColumn(): string
    {
        return 'id';
    }

    public function eventIdColumn(): string
    {
        return 'event_id';
    }

    public function aggregateRootIdColumn(): string
    {
        return 'aggregate_root_id';
    }

    public function versionColumn(): string
    {
        return 'version';
    }

    public function payloadColumn(): string
    {
        return 'payload';
    }

    public function additionalColumns(): array
    {
        return [
            'event_type' => '__event_type',
            'user_agent' => 'user-agent',
        ];
    }
}