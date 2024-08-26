<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace PhpOffice\PhpSpreadsheet\Writer\Ods\Cell;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;

/**
 * @author     Alexander Pervakov <frost-nzcr4@jagmort.com>
 */
class Comment
{
    public static function write(XMLWriter $objWriter, Cell $cell): void
    {
        $comments = $cell->getWorksheet()->getComments();
        if (!isset($comments[$cell->getCoordinate()])) {
            return;
        }
        $comment = $comments[$cell->getCoordinate()];

        $objWriter->startElement('office:annotation');
        $objWriter->writeAttribute('svg:width', $comment->getWidth());
        $objWriter->writeAttribute('svg:height', $comment->getHeight());
        $objWriter->writeAttribute('svg:x', $comment->getMarginLeft());
        $objWriter->writeAttribute('svg:y', $comment->getMarginTop());
        $objWriter->writeElement('dc:creator', $comment->getAuthor());
        $objWriter->writeElement('text:p', $comment->getText()->getPlainText());
        $objWriter->endElement();
    }
}
