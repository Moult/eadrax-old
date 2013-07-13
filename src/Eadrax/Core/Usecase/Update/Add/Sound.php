<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Sound extends Data\Sound implements Proposal
{
    public $project;
    public $private;
    public $file;
    public $thumbnail;
    public $length;
    public $filesize;
    private $repository;
    private $filemanager;
    private $soundeditor;
    private $validator;

    public function __construct(Data\Sound $sound, Repository $repository, Tool\Filemanager $filemanager, Tool\Soundeditor $soundeditor, Tool\Validator $validator)
    {
        $this->project = $sound->project;
        $this->private = $sound->private;
        $this->file = $sound->file;
        $this->repository = $repository;
        $this->filemanager = $filemanager;
        $this->soundeditor = $soundeditor;
        $this->validator = $validator;
    }

    public function validate()
    {
        $supported_filetypes = array('ogg', 'mp3', 'wav');

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

    public function submit()
    {
        $file_path = $this->repository->save_file(
            $this->file->name,
            $this->file->tmp_name,
            $this->file->mimetype,
            $this->file->filesize_in_bytes,
            $this->file->error_code
        );

        $this->id = $this->repository->save_sound(
            $this->project->id,
            $this->private,
            $file_path,
            $this->repository->save_generated_file($this->thumbnail),
            $this->length,
            $this->filesize
        );
    }

    public function get_id()
    {
        return $this->id;
    }

    public function generate_thumbnail()
    {
        $thumbnail_path = $this->file->tmp_name.'.thumb.png';
        $this->soundeditor->setup($this->file->tmp_name, $thumbnail_path);
        $this->soundeditor->thumbnail(300, 100);
        $this->thumbnail = $thumbnail_path;
    }

    public function calculate_length()
    {
        $this->soundeditor->setup($this->file->tmp_name);
        $this->length = $this->soundeditor->get_length();
    }

    public function calculate_filesize()
    {
        $this->filesize = $this->filemanager->get_file_size($this->file->tmp_name);
    }
}
