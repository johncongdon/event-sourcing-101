<?php

namespace ES101\ShoppingCart\HeaderDecorator;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDecorator;

class UserHeaderDecorator implements MessageDecorator
{
    public function __construct(private readonly int $user_id)
    {
    }

    public function decorate(Message $message): Message
    {
        return $message->withHeader('user_id', $this->user_id);
    }
}