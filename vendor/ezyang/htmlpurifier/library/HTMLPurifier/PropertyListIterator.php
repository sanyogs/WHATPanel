<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

/**
 * Property list iterator. Do not instantiate this class directly.
 */
class HTMLPurifier_PropertyListIterator extends FilterIterator
{

    /**
     * @type int
     */
    protected $l;
    /**
     * @type string
     */
    protected $filter;

    /**
     * @param Iterator $iterator Array of data to iterate over
     * @param string $filter Optional prefix to only allow values of
     */
    public function __construct(Iterator $iterator, $filter = null)
    {
        parent::__construct($iterator);
        $this->l = strlen($filter);
        $this->filter = $filter;
    }

    /**
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function accept()
    {
        $key = $this->getInnerIterator()->key();
        if (strncmp($key, $this->filter, $this->l) !== 0) {
            return false;
        }
        return true;
    }
}

// vim: et sw=4 sts=4
