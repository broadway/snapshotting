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

namespace Broadway\Snapshotting\Snapshot\Trigger;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Broadway\Snapshotting\Snapshot\Trigger;

class EventCountTrigger implements Trigger
{
    /**
     * @var int
     */
    private $eventCount;

    /**
     * @param int $eventCount
     */
    public function __construct($eventCount = 20)
    {
        $this->eventCount = $eventCount;
    }

    /**
     * {@inheritdoc}
     */
    public function shouldSnapshot(EventSourcedAggregateRoot $aggregateRoot)
    {
        $clonedAggregateRoot = clone $aggregateRoot;

        foreach ($clonedAggregateRoot->getUncommittedEvents() as $domainMessage) {
            if (0 === ($domainMessage->getPlayhead() + 1) % $this->eventCount) {
                return true;
            }
        }

        return false;
    }
}
