<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */


namespace Twilio;


abstract class Options implements \IteratorAggregate {
    protected $options = [];

    public function getIterator(): \Traversable {
        return new \ArrayIterator($this->options);
    }
}
