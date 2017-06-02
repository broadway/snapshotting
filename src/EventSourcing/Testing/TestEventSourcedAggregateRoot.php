<?php

/*
 * This file is part of the broadway/snapshotting package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Snapshotting\EventSourcing\Testing;

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
