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

namespace Broadway\Snapshotting\Snapshot\Snapshotter;

use Broadway\CommandHandling\CommandBus;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Broadway\Snapshotting\Snapshot\Command\ScheduleSnapshot;
use Broadway\Snapshotting\Snapshot\Snapshotter;

class CommandDispatchingSnapshotter implements Snapshotter
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function takeSnapshot(EventSourcedAggregateRoot $aggregateRoot)
    {
        $this->commandBus->dispatch(
            new ScheduleSnapshot(
                $aggregateRoot->getAggregateRootId(),
                get_class($aggregateRoot)
            )
        );
    }
}
