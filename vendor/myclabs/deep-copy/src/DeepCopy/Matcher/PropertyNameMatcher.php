<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace DeepCopy\Matcher;

/**
 * @final
 */
class PropertyNameMatcher implements Matcher
{
    /**
     * @var string
     */
    private $property;

    /**
     * @param string $property Property name
     */
    public function __construct($property)
    {
        $this->property = $property;
    }

    /**
     * Matches a property by its name.
     *
     * {@inheritdoc}
     */
    public function matches($object, $property)
    {
        return $property == $this->property;
    }
}
