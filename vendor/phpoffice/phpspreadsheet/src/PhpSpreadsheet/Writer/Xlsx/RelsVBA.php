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

use PhpOffice\PhpSpreadsheet\Reader\Xlsx\Namespaces;
use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;

class RelsVBA extends WriterPart
{
    /**
     * Write relationships for a signed VBA Project.
     *
     * @return string XML Output
     */
    public function writeVBARelationships()
    {
        // Create XML writer
        $objWriter = null;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $objWriter = new XMLWriter(XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $objWriter = new XMLWriter(XMLWriter::STORAGE_MEMORY);
        }

        // XML header
        $objWriter->startDocument('1.0', 'UTF-8', 'yes');

        // Relationships
        $objWriter->startElement('Relationships');
        $objWriter->writeAttribute('xmlns', Namespaces::RELATIONSHIPS);
        $objWriter->startElement('Relationship');
        $objWriter->writeAttribute('Id', 'rId1');
        $objWriter->writeAttribute('Type', Namespaces::VBA_SIGNATURE);
        $objWriter->writeAttribute('Target', 'vbaProjectSignature.bin');
        $objWriter->endElement();
        $objWriter->endElement();

        return $objWriter->getData();
    }
}
