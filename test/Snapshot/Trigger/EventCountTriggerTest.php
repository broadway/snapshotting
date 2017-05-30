<?php

/*
 * This file is part of the broadway/snapshotting package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Snapshotting\Snapshot\Trigger;

use Broadway\Snapshotting\EventSourcing\Testing\TestEventSourcedAggregateRoot;
use PHPUnit_Framework_TestCase;

class EventCountTriggerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_snapshot_when_number_of_uncommitted_events_exceeds_given_event_count()
    {
        $trigger = new EventCountTrigger(1);

        $aggregateRoot = new TestEventSourcedAggregateRoot();
        $this->assertFalse($trigger->shouldSnapshot($aggregateRoot));

        $aggregateRoot->apply(new \stdClass());
        $this->assertTrue($trigger->shouldSnapshot($aggregateRoot));

        $aggregateRoot->apply(new \stdClass());
        $this->assertTrue($trigger->shouldSnapshot($aggregateRoot));
    }
}
