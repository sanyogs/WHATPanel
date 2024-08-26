<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace DeepCopy\Filter\Doctrine;

use DeepCopy\Filter\Filter;
use DeepCopy\Reflection\ReflectionHelper;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @final
 */
class DoctrineEmptyCollectionFilter implements Filter
{
    /**
     * Sets the object property to an empty doctrine collection.
     *
     * @param object   $object
     * @param string   $property
     * @param callable $objectCopier
     */
    public function apply($object, $property, $objectCopier)
    {
        $reflectionProperty = ReflectionHelper::getProperty($object, $property);
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($object, new ArrayCollection());
    }
} 