<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace PhpOffice\PhpSpreadsheet\RichText;

class TextElement implements ITextElement
{
    /**
     * Text.
     *
     * @var string
     */
    private $text;

    /**
     * Create a new TextElement instance.
     *
     * @param string $text Text
     */
    public function __construct($text = '')
    {
        // Initialise variables
        $this->text = $text;
    }

    /**
     * Get text.
     *
     * @return string Text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set text.
     *
     * @param string $text Text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get font. For this class, the return value is always null.
     *
     * @return null|\PhpOffice\PhpSpreadsheet\Style\Font
     */
    public function getFont()
    {
        return null;
    }

    /**
     * Get hash code.
     *
     * @return string Hash code
     */
    public function getHashCode()
    {
        return md5(
            $this->text .
            __CLASS__
        );
    }
}
