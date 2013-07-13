<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Tool;

interface Videoeditor
{
    /**
     * Sets the source video to manipulate, and the destination to save the result
     *
     * Example:
     * $videoeditor->setup('/tmp/myfile.avi', '/tmp/myfile.mp4');
     *
     * @param string $source      The path to the source video file
     * @param string $destination The path of the destination file. If blank,
     *                            the source file will be overwritten.
     *
     * @return void
     */
    public function setup($source, $destination = NULL);

    /**
     * Retrieves the video dimensions in pixels of a file
     *
     * Example:
     * $dimensions = $videoeditor->get_video_dimensions();
     * $width = $dimensions[0];
     * $height = $dimensions[1];
     *
     * @return Array width, height
     */
    public function get_dimensions();

    /**
     * Retrives the length of a video in seconds
     *
     * Example:
     * $length = $videoeditor->get_video_length();
     *
     * @return int
     */
    public function get_length();

    /**
     * Gets a video screenshot from the mid-point of the video
     *
     * Example:
     * $image->thumbnail_video();
     *
     * @return void
     */
    public function thumbnail();

    /**
     * Encodes the video to a HTML5 friendly webm format
     *
     * Example:
     * $videoeditor->encode_to_webm();
     *
     * @return void
     */
    public function encode_to_webm();
}
