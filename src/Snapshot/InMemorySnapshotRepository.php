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

class InMemorySnapshotRepository implements SnapshotRepository
{
    /**
     * @var Snapshot[]
     */
    private $store = [];

    /**
     * @param mixed $id should be unique across aggregate types
     *
     * @return Snapshot|null
     */
    public function load($id)
    {
        return isset($this->store[$id]) ? $this->store[$id] : null;
    }

    /**
     * @param Snapshot $snapshot
     */
    public function save(Snapshot $snapshot)
    {
        // Clone to prevent unintentional mutation.
        return $this->store[$snapshot->getAggregateRoot()->getAggregateRootId()] =
            new Snapshot(clone $snapshot->getAggregateRoot());
    }
}