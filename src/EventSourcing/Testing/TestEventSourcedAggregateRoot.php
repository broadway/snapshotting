<?php

declare(strict_types=1);

namespace othillo\Broadway\Snapshotting\EventSourcing\Testing;

use Broadway\EventSourcing\EventSourcedAggregateRoot;

class TestEventSourcedAggregateRoot extends EventSourcedAggregateRoot
{
    /**
     * {@inheritdoc}
     */
    public function getAggregateRootId()
    {
        return 42;
    }
}
