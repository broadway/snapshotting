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

namespace Broadway\Snapshotting\Snapshot\Command;

final class ScheduleSnapshot
{
    /**
     * @var string
     */
    private $aggregateRootId;

    /**
     * @var string
     */
    private $aggregateClass;

    /**
     * @param string $aggregateRootId
     * @param string $aggregateClass
     */
    public function __construct($aggregateRootId, $aggregateClass)
    {
        $this->aggregateRootId = $aggregateRootId;
        $this->aggregateClass = $aggregateClass;
    }

    /**
     * @return string
     */
    public function getAggregateRootId()
    {
        return $this->aggregateRootId;
    }

    /**
     * @return string
     */
    public function getAggregateClass()
    {
        return $this->aggregateClass;
    }
}
