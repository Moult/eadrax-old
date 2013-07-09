<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Tool;

interface Photoshopper
{
    /**
     * Sets the source image to manipulate, and the destination to save the result
     *
     * Example:
     * $photoshopper->setup('/tmp/myfile.png', '/home/user/profile.png');
     *
     * @param string $source      The path to the source image file
     * @param string $destination The path of the destination image file. If
     *                            blank, the source file will be overwritten.
     *
     * @return void
     */
    public function setup($source, $destination = NULL);

    /**
     * Returns image dimensions
     *
     * @return array($width, $height) in pixels
     */
    public function get_dimensions();

    /**
     * Resizes an image to a height in px, maintaining aspect ratio
     *
     * Example:
     * $image->thumbnail_image('path/to/file.png');
     *
     * @param string $path Path to the image file
     * @return void
     */
    public function resize_to_height($path);
}
