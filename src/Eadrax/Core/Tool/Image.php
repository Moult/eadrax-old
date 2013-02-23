<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Tool;

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
