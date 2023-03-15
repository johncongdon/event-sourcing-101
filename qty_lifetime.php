<?php

require 'vendor/autoload.php';
require 'bootstrap.php';

use EventSauce\EventSourcing\ReplayingMessages\ReplayMessages;
use EventSauce\EventSourcing\OffsetCursor;
global $conn, $messageRepository;

$replayMessages = new ReplayMessages(
    $messageRepository,
    new \ES101\ShoppingCart\Projector\LifetimeQtyProjector($conn),
);

$sth = $conn->executeQuery("SELECT stream_offset FROM replay WHERE stream_name='lifetime_qty'");

$offset = $sth->fetchAssociative()['stream_offset'] ?? 0;

$cursor = OffsetCursor::fromStart(limit: 100);
if ($offset) {
    $cursor = OffsetCursor::fromOffset($offset);
}

process_batch:
$result = $replayMessages->replayBatch($cursor);
$cursor = $result->cursor();

if ($result->messagesHandled() > 0) {
    $conn->executeQuery("INSERT INTO replay VALUES ('lifetime_qty', :offset) ON DUPLICATE KEY UPDATE stream_offset=stream_offset+:offset", [
        'offset' => $result->messagesHandled(),
    ]);
    goto process_batch;
}
