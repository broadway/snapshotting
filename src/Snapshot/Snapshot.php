<?php

declare(strict_types=1);

/*
 * This file is part of the broadway/snapshotting package.
 *
 * (c) 2020 Broadway project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Snapshotting\Snapshot;

use Broadway\EventSourcing\EventSourcedAggregateRoot;

class Snapshot
{
    private $playhead;
    private $aggregateRoot;

    public function __construct(EventSourcedAggregateRoot $aggregateRoot)
    {
        $this->aggregateRoot = $aggregateRoot;
        $this->playhead = $aggregateRoot->getPlayhead();
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
