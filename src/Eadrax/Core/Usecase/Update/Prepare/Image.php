<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Prepare;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Image extends Data\Image implements Proposal
{
    public $project;
    public $private;
    public $file;
    public $thumbnail;
    public $width;
    public $height;
    private $photoshopper;
    private $validator;

    public function __construct(Data\Image $image, Tool\Photoshopper $photoshopper, Tool\Validator $validator)
    {
        $this->project = $image->project;
        $this->private = $image->private;
        $this->file = $image->file;
        $this->photoshopper = $photoshopper;
        $this->validator = $validator;
    }

    public function validate()
    {
        $supported_filetypes = array('gif', 'jpg', 'jpeg', 'png');

        $this->validator->setup(array(
            'file' => array(
                'name' => $this->file->name,
                'tmp_name' => $this->file->tmp_name,
                'type' => $this->file->mimetype,
                'size' => $this->file->filesize_in_bytes,
                'error' => $this->file->error_code
            )
        ));
        $this->validator->rule('image', 'not_empty');
        $this->validator->rule('image', 'upload_valid');
        $this->validator->rule('image', 'upload_type', $supported_filetypes);
        $this->validator->rule('image', 'upload_size', '100M');

        if ( ! $this->validator->check())
            throw new Exception\Validation($this->validator->errors());
    }

    public function generate_thumbnail()
    {
        $thumbnail_path = $this->file->tmp_name.'.thumb.png';
        $this->photoshopper->setup($this->file->tmp_name, $thumbnail_path);
        $this->photoshopper->resize_to_height(100);
        $this->thumbnail = $thumbnail_path;
    }

    public function calculate_dimensions()
    {
        $this->photoshopper->setup($this->file->tmp_name);
        list($this->width, $this->height) = $this->photoshopper->get_dimensions();
    }
}
