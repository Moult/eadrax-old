<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Tool;

interface Filesystem
{
    /**
     * Retrieves the image dimensions in pixels of a file
     *
     * Example:
     * $dimensions = $filesystem->get_image_dimensions('path/to/file.png');
     * $width = $dimensions['width'];
     * $height = $dimensions ['height'];
     *
     * @return Array width, height
     */
    public function get_image_dimensions($path_to_file);

    /**
     * Retrieves the video dimensions in pixels of a file
     *
     * Example:
     * $dimensions = $filesystem->get_video_dimensions('path/to/file.avi');
     * $width = $dimensions['width'];
     * $height = $dimensions ['height'];
     *
     * @return Array width, height
     */
    public function get_video_dimensions($path_to_file);

    /**
     * Retrives the length of a video in seconds
     *
     * Example:
     * $length = $filesystem->get_video_length('path/to/file.avi');
     *
     * @return int
     */
    public function get_video_length($path_to_file);

    /**
     * Retrives the length of a sound in seconds
     *
     * Example:
     * $length = $filesystem->get_sound_length('path/to/file.mp3');
     *
     * @return int
     */
    public function get_sound_length($path_to_file);

    /**
     * Retrieves the filesize of a file in bytes
     *
     * Example:
     * $file_size = $filesystem->get_file_size('path/to/file');
     *
     * @return int
     */
    public function get_file_size($path_to_file);
}
