<?php
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

abstract class SnapshotRepositoryTest extends \PHPUnit_Framework_TestCase
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

        // Applying another event to Snapshotted Aggregate should not affect Snapshot version
        $aggregate->apply(new MyEvent());
        $aggregate->getUncommittedEvents();

        $this->assertEquals(
            new Snapshot($this->createAggregateWithHistory(5)),
            $this->repository->load(42)
        );
    }

    /**
     * @return SnapshotRepository
     */
    protected abstract function createRepository();

    protected function setUp()
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
        for ($i = 0; $i < $numberOfEvents; $i++) {
            $aggregate->apply(new MyEvent());
        }
        $aggregate->getUncommittedEvents(); // Flush events
        return $aggregate;
    }
}

final class MyAggregate extends EventSourcedAggregateRoot
{
    private $foo = 0;

    /**
     * @return string
     */
    public function getAggregateRootId()
    {
        return 42;
    }

    protected function applyMyEvent()
    {
        $this->foo += 5;
    }
}

final class MyEvent
{
}