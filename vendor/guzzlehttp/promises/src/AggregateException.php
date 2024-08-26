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

/**
 * Exception thrown when too many errors occur in the some() or any() methods.
 */
class AggregateException extends RejectionException
{
    public function __construct(string $msg, array $reasons)
    {
        parent::__construct(
            $reasons,
            sprintf('%s; %d rejected promises', $msg, count($reasons))
        );
    }
}
