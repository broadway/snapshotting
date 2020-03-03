<?php

declare(strict_types=1);

/*
 * This file is part of the broadway/snapshotting package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Snapshotting\EventSourcing;

use Broadway\Domain\DomainEventStream;
use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use Broadway\Snapshotting\EventSourcing\Testing\TestEventSourcedAggregateRoot;
use Broadway\Snapshotting\Snapshot\Snapshot;
use Broadway\Snapshotting\Snapshot\SnapshotRepository;
use Broadway\Snapshotting\Snapshot\Snapshotter;
use Broadway\Snapshotting\Snapshot\Trigger\EventCountTrigger;
use Prophecy\Argument;

class SnapshottingEventSourcingRepositoryTest extends \PHPUnit\Framework\TestCase
{
    private $eventStore;
    private $eventSourcingRepository;
    private $snapshotRepository;
    private $snapshottingEventSourcingRepository;
    private $snapshotter;

    public function setUp(): void
    {
        $this->eventSourcingRepository = $this->prophesize(EventSourcingRepository::class);
        $this->eventStore = $this->prophesize(EventStore::class);
        $this->snapshotRepository = $this->prophesize(SnapshotRepository::class);
        $this->snapshotter = $this->prophesize(Snapshotter::class);

        $this->snapshottingEventSourcingRepository = new SnapshottingEventSourcingRepository(
            $this->eventSourcingRepository->reveal(),
            $this->eventStore->reveal(),
            $this->snapshotRepository->reveal(),
            new EventCountTrigger(100),
            $this->snapshotter->reveal()
        );
    }

    /**
     * @test
     */
    public function it_reconstitutes_aggregate_when_no_snapshot_found()
    {
        $this->snapshotRepository
            ->load(42)
            ->shouldBeCalled()
            ->willReturn(null)
        ;

        $this->eventSourcingRepository
            ->load(42)
            ->shouldBeCalled()
            ->willReturn($this->prophesize(EventSourcedAggregateRoot::class)->reveal())
        ;

        $this->snapshottingEventSourcingRepository->load(42);
    }

    /**
     * @test
     */
    public function it_queries_the_event_store_for_events_recorded_after_playhead_of_snapshot()
    {
        // create a snapshot of an aggregate root with 5 committed events
        $snapshot = $this->createSnapshot();
        $this->assertEquals(4, $snapshot->getPlayhead());

        $this->snapshotRepository
            ->load(42)
            ->shouldBeCalled()
            ->willReturn($snapshot)
        ;

        $this->eventSourcingRepository
            ->load(42)
            ->shouldNotBeCalled()
        ;

        // 2 new events since last snapshot
        $domainEventStream = new DomainEventStream([
            DomainMessage::recordNow(42, 5, new Metadata([]), new \stdClass()),
            DomainMessage::recordNow(42, 6, new Metadata([]), new \stdClass()),
        ]);

        $this->eventStore
            ->loadFromPlayhead(42, 5)
            ->shouldBeCalled()
            ->willReturn($domainEventStream)
        ;

        $aggregateRoot = $this->snapshottingEventSourcingRepository->load(42);
        $this->assertEquals(6, $aggregateRoot->getPlayhead());
    }

    /**
     * @test
     */
    public function it_does_not_take_a_snapshot_when_threshold_not_reached()
    {
        $aggregateRoot = $this->createAggregateRootWithEvents(99);

        $this->snapshotRepository
            ->save(Argument::type(EventSourcedAggregateRoot::class))
            ->shouldNotBeCalled()
        ;

        $this->snapshottingEventSourcingRepository->save($aggregateRoot);
    }

    /**
     * @test
     */
    public function it_takes_a_snapshot_when_threshold_reached()
    {
        $aggregateRoot = $this->createAggregateRootWithEvents(100);

        $this->snapshotter
            ->takeSnapshot(Argument::type(EventSourcedAggregateRoot::class))
            ->shouldBeCalled()
        ;

        $this->snapshottingEventSourcingRepository->save($aggregateRoot);
    }

    private function createSnapshot()
    {
        $aggregateRoot = $this->createAggregateRootWithEvents(5);
        $aggregateRoot->getUncommittedEvents();

        return new Snapshot($aggregateRoot);
    }

    private function createAggregateRootWithEvents($countEvents)
    {
        $aggregateRoot = new TestEventSourcedAggregateRoot();

        for ($i = 0; $i < $countEvents; ++$i) {
            $aggregateRoot->apply(new \stdClass());
        }

        return $aggregateRoot;
    }
}
