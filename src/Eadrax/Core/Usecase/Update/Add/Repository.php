<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;

interface Repository
{
    /**
     * @return array($project_name, $author_id, $author_username);
     */
    public function get_project_name_and_author_id_and_username($project_id);

    /**
     * @return int Unique ID of the saved update
     */
    public function save_text($project_id, $update_private, $text_message);

    /**
     * @return int Unique ID of the saved update
     */
    public function save_paste($project_id, $update_private, $paste_text, $paste_syntax);

    /**
     * @return string Path to saved file
     */
    public function save_file($name, $tmp_name, $type, $size, $error);

    /**
     * @return string Path to saved file
     */
    public function save_generated_file($tmp_name);

    /**
     * @return int Unique ID of the saved update
     */
    public function save_image($project_id, $update_private, $image_file_path, $image_thumbnail_path, $image_width, $image_height);

    /**
     * @return int Unique ID of the saved update
     */
    public function save_sound($project_id, $update_private, $sound_file_path, $sound_thumbnail_path, $sound_length, $sound_filesize);

    /**
     * @return int Unique ID of the saved update
     */
    public function save_video($project_id, $update_private, $video_file_path, $video_thumbnail_path, $video_length, $video_filesize, $video_width, $video_height);
}
