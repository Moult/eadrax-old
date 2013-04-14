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
     * $image->thumbnail_image('path/to/file.png', 'path/to/thumbnail.png');
     *
     * @param string $load Path to the image file
     * @param string $save The path to save the thumbnail to
     * @return void
     */
    public function thumbnail_image($load, $save);

    /**
     * Thumbnails a video to a height in px, maintaining aspect ratio
     *
     * The thumbnail is automatically taken from the mid-point of the video
     *
     * Example:
     * $image->thumbnail_video('path/to/file.avi', 'path/to/thumbnail.png');
     *
     * @param string $load Path to the video file
     * @param string $save The path to save the thumbnail to
     * @return void
     */
    public function thumbnail_video($load, $save);

    /**
     * Thumbnails a sound to a height in px, maintaining aspect ratio
     *
     * The thumbnail is essentially a sound wave.
     *
     * Example:
     * $image->thumbnail_sound('path/to/file.avi', 'path/to/thumbnail.png');
     *
     * @param string $load Path to the sound file
     * @param string $save The path to save the thumbnail to
     * @return void
     */
    public function thumbnail_sound($load, $save);

    /**
     * Screenshots a website, and generates a thumbnail of it
     *
     * Example:
     * $image->screenshot_website('http://foo.com', '/path/to/thumbnail.png');
     *
     * @param string $url The website to screenshot
     * @param string $save The path to save the thumbnail to
     * @return void
     */
    public function screenshot_website($url, $save);
}
