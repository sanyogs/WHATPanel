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
 * This file is part of FPDI
 *
 * @package   setasign\Fpdi
 * @copyright Copyright (c) 2023 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @license   http://opensource.org/licenses/mit-license The MIT License
 */

namespace setasign\Fpdi\Tfpdf;

use setasign\Fpdi\FpdfTplTrait;

/**
 * Class FpdfTpl
 *
 * We need to change some access levels and implement the setPageFormat() method to bring back compatibility to tFPDF.
 */
class FpdfTpl extends \tFPDF
{
    use FpdfTplTrait;
}
