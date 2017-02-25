<?php

declare(strict_types=1);

namespace othillo\Broadway\Snapshotting\Snapshot;

use Broadway\EventSourcing\EventSourcedAggregateRoot;

interface Trigger
{
    public function shouldSnapshot(EventSourcedAggregateRoot $aggregateRoot): bool;
}
