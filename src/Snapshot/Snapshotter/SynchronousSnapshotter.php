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

namespace Broadway\Snapshotting\Snapshot\Snapshotter;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Broadway\Snapshotting\Snapshot\Snapshot;
use Broadway\Snapshotting\Snapshot\SnapshotRepository;
use Broadway\Snapshotting\Snapshot\Snapshotter;

class SynchronousSnapshotter implements Snapshotter
{
    /**
     * @var SnapshotRepository
     */
    private $snapshotRepository;

    public function __construct(SnapshotRepository $snapshotRepository)
    {
        $this->snapshotRepository = $snapshotRepository;
    }

    public function takeSnapshot(EventSourcedAggregateRoot $aggregateRoot)
    {
        $this->snapshotRepository->save(new Snapshot($aggregateRoot));
    }
}
