<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Prepare;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Video extends Data\Video implements Proposal
{
    public $project;
    public $private;
    public $file;
    public $thumbnail;
    public $length;
    public $filesize;
    public $width;
    public $height;
    private $filemanager;
    private $videoeditor;
    private $validator;

    public function __construct(Data\Video $video, Tool\Filemanager $filemanager, Tool\Videoeditor $videoeditor, Tool\Validator $validator)
    {
        $this->project = $video->project;
        $this->private = $video->private;
        $this->file = $video->file;
        $this->filemanager = $filemanager;
        $this->videoeditor = $videoeditor;
        $this->validator = $validator;
    }

    public function validate()
    {
        $supported_filetypes = array('ogg', 'ogv', 'wmv', 'avi', 'mpg', 'mpeg', 'mov');

        $this->validator->setup(array(
            'file' => array(
                'name' => $this->file->name,
                'tmp_name' => $this->file->tmp_name,
                'type' => $this->file->mimetype,
                'size' => $this->file->filesize_in_bytes,
                'error' => $this->file->error_code
            )
        ));
        $this->validator->rule('file', 'not_empty');
        $this->validator->rule('file', 'upload_valid');
        $this->validator->rule('file', 'upload_type', $supported_filetypes);
        $this->validator->rule('file', 'upload_size', '100M');

        if ( ! $this->validator->check())
            throw new Exception\Validation($this->validator->errors());
    }

    public function encode_to_webm()
    {
        $webm_path = $this->file->tmp_name.'.webm';
        $this->videoeditor->setup($this->file->tmp_name, $webm_path);
        $this->videoeditor->encode_to_webm();
        $this->file = $webm_path;
    }

    public function generate_thumbnail()
    {
        $thumbnail_path = $this->file.'.thumb.png';
        $this->videoeditor->setup($this->file, $thumbnail_path);
        $this->videoeditor->thumbnail(300, 100);
        $this->thumbnail = $thumbnail_path;
    }

    public function calculate_length()
    {
        $this->videoeditor->setup($this->file);
        $this->length = $this->videoeditor->get_length();
    }

    public function calculate_filesize()
    {
        $this->filesize = $this->filemanager->get_file_size($this->file);
    }

    public function calculate_dimensions()
    {
        $this->videoeditor->setup($this->file);
        list($this->width, $this->height) = $this->videoeditor->get_dimensions();
    }
}
