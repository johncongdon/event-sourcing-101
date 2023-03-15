<?php

require 'vendor/autoload.php';
require 'bootstrap.php';

use EventSauce\EventSourcing\ReplayingMessages\ReplayMessages;
use EventSauce\EventSourcing\OffsetCursor;
global $messageRepository;

$replayMessages = new ReplayMessages(
    $messageRepository,
    new LifeTimeQty(),
);

$cursor = OffsetCursor::fromStart(limit: 100);

process_batch:
$result = $replayMessages->replayBatch($cursor);
$cursor = $result->cursor();

if ($result->messagesHandled() > 0) {
    goto process_batch;
}
