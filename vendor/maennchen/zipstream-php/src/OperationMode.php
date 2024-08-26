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

namespace ZipStream;

/**
 * ZipStream execution operation modes
 */
enum OperationMode
{
    /**
     * Stream file into output stream
     */
    case NORMAL;

    /**
     * Simulate the zip to figure out the resulting file size
     *
     * This only supports entries where the file size is known beforehand and
     * deflation is disabled.
     */
    case SIMULATE_STRICT;

    /**
     * Simulate the zip to figure out the resulting file size
     *
     * If the file size is not known beforehand or deflation is enabled, the
     * entry streams will be read and rewound.
     *
     * If the entry does not support rewinding either, you will not be able to
     * use the same stream in a later operation mode like `NORMAL`.
     */
    case SIMULATE_LAX;
}
