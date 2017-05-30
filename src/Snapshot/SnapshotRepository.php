<?php

namespace othillo\Broadway\Snapshotting\Snapshot;

interface SnapshotRepository
{
    /**
     * @param mixed $id should be unique across aggregate types
     *
     * @return Snapshot
     *
     * @throws SnapshotNotFoundException
     */
    public function load($id);

    /**
     * @param Snapshot $snapshot
     */
    public function save(Snapshot $snapshot);
}
