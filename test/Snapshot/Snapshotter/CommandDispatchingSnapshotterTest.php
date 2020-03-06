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

class CommandDispatchingSnapshotterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var CommandDispatchingSnapshotter
     */
    private $snapshotter;

    /**
     * @test
     */
    public function it_dispatches_ScheduleSnapshot_command()
    {
        $this->commandBus
            ->dispatch(
                new ScheduleSnapshot(
                    42,
                    'Broadway\Snapshotting\Snapshot\Snapshotter\MyOtherAggregate'
                )
            )
            ->shouldBeCalled();

        $this->snapshotter->takeSnapshot(new MyOtherAggregate());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->commandBus = $this->prophesize(CommandBus::class);
        $this->snapshotter = new CommandDispatchingSnapshotter($this->commandBus->reveal());
    }
}

final class MyOtherAggregate extends EventSourcedAggregateRoot
{
    public function getAggregateRootId(): string
    {
        return '42';
    }
}
