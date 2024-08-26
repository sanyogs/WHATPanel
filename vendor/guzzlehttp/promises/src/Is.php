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

final class Is
{
    /**
     * Returns true if a promise is pending.
     */
    public static function pending(PromiseInterface $promise): bool
    {
        return $promise->getState() === PromiseInterface::PENDING;
    }

    /**
     * Returns true if a promise is fulfilled or rejected.
     */
    public static function settled(PromiseInterface $promise): bool
    {
        return $promise->getState() !== PromiseInterface::PENDING;
    }

    /**
     * Returns true if a promise is fulfilled.
     */
    public static function fulfilled(PromiseInterface $promise): bool
    {
        return $promise->getState() === PromiseInterface::FULFILLED;
    }

    /**
     * Returns true if a promise is rejected.
     */
    public static function rejected(PromiseInterface $promise): bool
    {
        return $promise->getState() === PromiseInterface::REJECTED;
    }
}
