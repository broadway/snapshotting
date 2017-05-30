<?php

namespace othillo\Broadway\Snapshotting\Snapshot;

use Broadway\EventSourcing\EventSourcedAggregateRoot;

class Snapshot
{
    private $playhead;
    private $aggregateRoot;

    /**
     * @param EventSourcedAggregateRoot $aggregateRoot
     */
    public function __construct(EventSourcedAggregateRoot $aggregateRoot)
    {
        $this->aggregateRoot = $aggregateRoot;
        $this->playhead      = $aggregateRoot->getPlayhead();
    }

    /**
     * @return int
     */
    public function getPlayhead()
    {
        return $this->playhead;
    }

    /**
     * @return EventSourcedAggregateRoot
     */
    public function getAggregateRoot()
    {
        return $this->aggregateRoot;
    }
}
