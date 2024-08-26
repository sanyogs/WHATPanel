<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

declare(strict_types=1);

namespace GuzzleHttp\Promise;

interface TaskQueueInterface
{
    /**
     * Returns true if the queue is empty.
     */
    public function isEmpty(): bool;

    /**
     * Adds a task to the queue that will be executed the next time run is
     * called.
     */
    public function add(callable $task): void;

    /**
     * Execute all of the pending task in the queue.
     */
    public function run(): void;
}
