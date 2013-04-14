<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Tool;

interface Image
{
    /**
     * Resizes an image to a height in px, maintaining aspect ratio
     *
     * Example:
     * $image->thumbnail_image('path/to/file.png');
     *
     * @param string $path Path to the image file
     * @return void
     */
    public function thumbnail_image($path);

    /**
     * Thumbnails a video to a height in px, maintaining aspect ratio
     *
     * The thumbnail is automatically taken from the mid-point of the video
     *
     * Example:
     * $image->thumbnail_video('path/to/file.avi');
     *
     * @param string $path Path to the video file
     * @return void
     */
    public function thumbnail_video($path);

    /**
     * Thumbnails a sound to a height in px, maintaining aspect ratio
     *
     * The thumbnail is essentially a sound wave.
     *
     * Example:
     * $image->thumbnail_sound('path/to/file.avi');
     *
     * @param string $path Path to the sound file
     * @return void
     */
    public function thumbnail_sound($path);

    /**
     * Screenshots a website, and generates a thumbnail of it
     *
     * Example:
     * $image->screenshot_website('http://foo.com');
     *
     * @param string $url The website to screenshot
     * @return void
     */
    public function screenshot_website($url);
}
