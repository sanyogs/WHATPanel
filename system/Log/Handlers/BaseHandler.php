<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\Log\Handlers;

/**
 * Base class for logging
 */
abstract class BaseHandler implements HandlerInterface
{
    /**
     * Handles
     *
     * @var array
     */
    protected $handles;

    /**
     * Date format for logging
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Constructor
     */
    public function __construct(array $config)
    {
        $this->handles = $config['handles'] ?? [];
    }

    /**
     * Checks whether the Handler will handle logging items of this
     * log Level.
     */
    public function canHandle(string $level): bool
    {
        return in_array($level, $this->handles, true);
    }

    /**
     * Stores the date format to use while logging messages.
     */
    public function setDateFormat(string $format): HandlerInterface
    {
        $this->dateFormat = $format;

        return $this;
    }
}
