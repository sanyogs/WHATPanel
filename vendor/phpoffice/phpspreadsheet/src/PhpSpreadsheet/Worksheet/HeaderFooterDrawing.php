<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace PhpOffice\PhpSpreadsheet\Worksheet;

class HeaderFooterDrawing extends Drawing
{
    /**
     * Get hash code.
     *
     * @return string Hash code
     */
    public function getHashCode()
    {
        return md5(
            $this->getPath() .
            $this->name .
            $this->offsetX .
            $this->offsetY .
            $this->width .
            $this->height .
            __CLASS__
        );
    }
}
