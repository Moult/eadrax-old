<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Tool;

interface Soundeditor
{
    /**
     * Sets the source sound to manipulate, and the destination to save the result
     *
     * Example:
     * $photoshopper->setup('/tmp/myfile.wav', '/home/user/profile.mp3');
     *
     * @param string $source      The path to the source sound file
     * @param string $destination The path of the destination file. If blank, the
     *                            source file will be overwritten.
     *
     * @return void
     */
    public function setup($source, $destination = NULL);

    /**
     * Retrieves the length of a sound in seconds
     *
     * Example:
     * $length = $soundeditor->get_length();
     *
     * @return int
     */
    public function get_length();

    /**
     * Thumbnails a sound to specific dimensions, maintaining aspect ratio
     *
     * The thumbnail is essentially a sound wave.
     *
     * Example:
     * $image->thumbnail(500, 300); # Produces 500x300 image
     *
     * @param string $path Path to the sound file
     * @return void
     */
    public function thumbnail($width, $height);
}
