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

/**
 * @final
 */
class DoctrineCollectionFilter implements Filter
{
    /**
     * Copies the object property doctrine collection.
     *
     * {@inheritdoc}
     */
    public function apply($object, $property, $objectCopier)
    {
        $reflectionProperty = ReflectionHelper::getProperty($object, $property);

        $reflectionProperty->setAccessible(true);
        $oldCollection = $reflectionProperty->getValue($object);

        $newCollection = $oldCollection->map(
            function ($item) use ($objectCopier) {
                return $objectCopier($item);
            }
        );

        $reflectionProperty->setValue($object, $newCollection);
    }
}
