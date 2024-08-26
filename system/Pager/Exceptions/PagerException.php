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

namespace CodeIgniter\Pager\Exceptions;

use CodeIgniter\Exceptions\FrameworkException;

class PagerException extends FrameworkException
{
    /**
     * Throws when the template is invalid.
     *
     * @return static
     */
    public static function forInvalidTemplate(?string $template = null)
    {
        return new static(lang('hd_lang.Pager.invalidTemplate', [$template]));
    }

    /**
     * Throws when the group is invalid.
     *
     * @return static
     */
    public static function forInvalidPaginationGroup(?string $group = null)
    {
        return new static(lang('hd_lang.Pager.invalidPaginationGroup', [$group]));
    }
}
