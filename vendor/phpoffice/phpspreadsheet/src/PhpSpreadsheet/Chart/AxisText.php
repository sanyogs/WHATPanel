<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace PhpOffice\PhpSpreadsheet\Chart;

use PhpOffice\PhpSpreadsheet\Style\Font;

class AxisText extends Properties
{
    /** @var ?int */
    private $rotation;

    /** @var Font */
    private $font;

    public function __construct()
    {
        parent::__construct();
        $this->font = new Font();
        $this->font->setSize(null, true);
    }

    public function setRotation(?int $rotation): self
    {
        $this->rotation = $rotation;

        return $this;
    }

    public function getRotation(): ?int
    {
        return $this->rotation;
    }

    public function getFillColorObject(): ChartColor
    {
        $fillColor = $this->font->getChartColor();
        if ($fillColor === null) {
            $fillColor = new ChartColor();
            $this->font->setChartColorFromObject($fillColor);
        }

        return $fillColor;
    }

    public function getFont(): Font
    {
        return $this->font;
    }

    public function setFont(Font $font): self
    {
        $this->font = $font;

        return $this;
    }
}
