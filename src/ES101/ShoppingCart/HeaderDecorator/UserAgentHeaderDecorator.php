<?php

namespace ES101\ShoppingCart\HeaderDecorator;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDecorator;

class UserAgentHeaderDecorator implements MessageDecorator
{

    public function decorate(Message $message): Message
    {
        return $message->withHeader('user-agent', $_SERVER['HTTP_USER_AGENT'] ?? 'cli');
    }
}