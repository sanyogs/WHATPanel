<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\Images\Exceptions;

use CodeIgniter\Exceptions\ExceptionInterface;
use CodeIgniter\Exceptions\FrameworkException;

class ImageException extends FrameworkException implements ExceptionInterface
{
    /**
     * Thrown when the image is not found.
     *
     * @return static
     */
    public static function forMissingImage()
    {
        return new static(lang('hd_lang.Images.sourceImageRequired'));
    }

    /**
     * Thrown when the file specific is not following the role.
     *
     * @return static
     */
    public static function forFileNotSupported()
    {
        return new static(lang('hd_lang.Images.fileNotSupported'));
    }

    /**
     * Thrown when the angle is undefined.
     *
     * @return static
     */
    public static function forMissingAngle()
    {
        return new static(lang('hd_lang.Images.rotationAngleRequired'));
    }

    /**
     * Thrown when the direction property is invalid.
     *
     * @return static
     */
    public static function forInvalidDirection(?string $dir = null)
    {
        return new static(lang('hd_lang.Images.invalidDirection', [$dir]));
    }

    /**
     * Thrown when the path property is invalid.
     *
     * @return static
     */
    public static function forInvalidPath()
    {
        return new static(lang('hd_lang.Images.invalidPath'));
    }

    /**
     * Thrown when the EXIF function is not supported.
     *
     * @return static
     */
    public static function forEXIFUnsupported()
    {
        return new static(lang('hd_lang.Images.exifNotSupported'));
    }

    /**
     * Thrown when the image specific is invalid.
     *
     * @return static
     */
    public static function forInvalidImageCreate(?string $extra = null)
    {
        return new static(lang('hd_lang.Images.unsupportedImageCreate') . ' ' . $extra);
    }

    /**
     * Thrown when the image save failed.
     *
     * @return static
     */
    public static function forSaveFailed()
    {
        return new static(lang('hd_lang.Images.saveFailed'));
    }

    /**
     * Thrown when the image library path is invalid.
     *
     * @return static
     */
    public static function forInvalidImageLibraryPath(?string $path = null)
    {
        return new static(lang('hd_lang.Images.libPathInvalid', [$path]));
    }

    /**
     * Thrown when the image process failed.
     *
     * @return static
     */
    public static function forImageProcessFailed()
    {
        return new static(lang('hd_lang.Images.imageProcessFailed'));
    }
}
