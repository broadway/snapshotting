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

namespace Broadway\Snapshotting\Snapshot;

interface SnapshotRepository
{
    /**
     * @param mixed $id should be unique across aggregate types
     *
     * @return Snapshot|null
     */
    public function load($id);

    public function save(Snapshot $snapshot);
}
