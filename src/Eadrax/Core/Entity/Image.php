<?php
/**
 * Eadrax Entity/Image.php
 *
 * @package   Entity
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Entity;

/**
 * Allows for image manipulation
 *
 * @package Entity
 */
interface Image
{
    /**
     * Resizes an image.
     *
     * Example:
     * $image->resize(100, 100);
     *
     * @param int $width  The width of the resized image in px
     * @param int $height The height of the resized image in px
     * @return void
     */
    public function resize($width, $height);
}
