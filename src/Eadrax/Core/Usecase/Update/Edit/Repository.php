<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Edit;

interface Repository
{
    /**
     * @return $project_author_id
     */
    public function get_author_id($update_id);

    /**
     * @return void
     */
    public function purge_files($update_id);

    /**
     * @return void
     */
    public function save_text($update_id, $update_private, $text_message);

    /**
     * @return void
     */
    public function save_paste($update_id, $update_private, $paste_text, $paste_syntax);

    /**
     * @return string Path to saved file
     */
    public function save_file($name, $tmp_name, $type, $size, $error);

    /**
     * @return string Path to saved file
     */
    public function save_generated_file($tmp_name);

    /**
     * @return void
     */
    public function save_image($update_id, $update_private, $image_file_path, $image_thumbnail_path, $image_width, $image_height);

    /**
     * @return void
     */
    public function save_sound($update_id, $update_private, $sound_file_path, $sound_thumbnail_path, $sound_length, $sound_filesize);

    /**
     * @return void
     */
    public function save_video($update_id, $update_private, $video_file_path, $video_thumbnail_path, $video_length, $video_filesize, $video_width, $video_height);

    /**
     * @return void
     */
    public function save_website($update_id, $update_private, $website_url, $website_thumbnail_path);
}
