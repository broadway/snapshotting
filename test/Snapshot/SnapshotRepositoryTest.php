<?php

declare(strict_types=1);
/**
 * This file is part of the broadway/snapshotting package.
 *
 *  (c) Qandidate.com <opensource@qandidate.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Broadway\Snapshotting\Snapshot;

use Broadway\EventSourcing\EventSourcedAggregateRoot;

abstract class SnapshotRepositoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var SnapshotRepository
     */
    protected $repository;

    /**
     * @test
     */
    public function it_implements_SnapshotRepository()
    {
        $this->assertInstanceOf(SnapshotRepository::class, $this->repository);
    }

    /**
     * @test
     */
    public function it_returns_null_when_no_snapshot_available()
    {
        $this->assertNull($this->repository->load(42));
    }

    /**
     * @test
     */
    public function it_returns_snapshot_when_available()
    {
        $aggregate = $this->createAggregateWithHistory(5);
        $this->repository->save(new Snapshot($aggregate));

        $this->assertEquals(
            new Snapshot($aggregate),
            $this->repository->load(42)
        );
    }

    /**
     * @test
     */
    public function it_does_not_mutate_state_of_Snapshot_Aggregate_after_persisting()
    {
        $aggregate = $this->createAggregateWithHistory(5);
        $this->repository->save(new Snapshot($aggregate));

        // Applying another event to Snapshotted Aggregate should not affect Snapshot version
        $aggregate->apply(new MyEvent());
        $aggregate->getUncommittedEvents();

        $snapshot = $this->repository->load(42);
        $this->assertEquals(new Snapshot($this->createAggregateWithHistory(5)), $snapshot);
    }

    /**
     * @test
     */
    public function it_does_not_mutate_state_of_Snapshot_Aggregate_after_loading()
    {
        $aggregate = $this->createAggregateWithHistory(5);
        $this->repository->save(new Snapshot($aggregate));

        $snapshot = $this->repository->load(42);
        $loadedAggregate = $snapshot->getAggregateRoot();
        $loadedAggregate->apply(new MyEvent());
        $loadedAggregate->getUncommittedEvents();

        $this->assertEquals(
            new Snapshot($this->createAggregateWithHistory(5)),
            $this->repository->load(42)
        );
    }

    /**
     * @return SnapshotRepository
     */
    abstract protected function createRepository();

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createRepository();
    }

    /**
     * @param int $numberOfEvents
     */
    private function createAggregateWithHistory($numberOfEvents)
    {
        $aggregate = new MyAggregate();
        for ($i = 0; $i < $numberOfEvents; ++$i) {
            $aggregate->apply(new MyEvent());
        }
        $aggregate->getUncommittedEvents(); // Flush events
        return $aggregate;
    }
}

final class MyAggregate extends EventSourcedAggregateRoot
{
    private $foo = 0;

    public function getAggregateRootId(): string
    {
        return '42';
    }

    protected function applyMyEvent()
    {
        $this->foo += 5;
    }
}

final class MyEvent
{
}
