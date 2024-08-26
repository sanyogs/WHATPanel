<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

abstract class WriterPart
{
    /**
     * Parent Xlsx object.
     *
     * @var Xlsx
     */
    private $parentWriter;

    /**
     * Get parent Xlsx object.
     *
     * @return Xlsx
     */
    public function getParentWriter()
    {
        return $this->parentWriter;
    }

    /**
     * Set parent Xlsx object.
     */
    public function __construct(Xlsx $writer)
    {
        $this->parentWriter = $writer;
    }
}
