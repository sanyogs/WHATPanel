<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace DeepCopy\TypeFilter\Date;

use DateInterval;
use DeepCopy\TypeFilter\TypeFilter;

/**
 * @final
 *
 * @deprecated Will be removed in 2.0. This filter will no longer be necessary in PHP 7.1+.
 */
class DateIntervalFilter implements TypeFilter
{

    /**
     * {@inheritdoc}
     *
     * @param DateInterval $element
     *
     * @see http://news.php.net/php.bugs/205076
     */
    public function apply($element)
    {
        $copy = new DateInterval('P0D');

        foreach ($element as $propertyName => $propertyValue) {
            $copy->{$propertyName} = $propertyValue;
        }

        return $copy;
    }
}
