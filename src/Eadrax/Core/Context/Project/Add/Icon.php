<?php
/**
 * Eadrax Context/Project/Add/Icon.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Context\Project\Add;
use Eadrax\Core\Data;
use Eadrax\Core\Context;
//use Eadrax\Core\Entity;
use Eadrax\Core\Exception;

/**
 * Allows data_file to be cast as a icon role
 *
 * @package    Context
 * @subpackage Role
 */
class Icon extends Data\File
{
    use Context\Interaction;

    /**
     * Takes a data object and copies all of its properties
     *
     * @param Data\File $data_file Data object to copy
     * @return void
     */
    public function __construct(Data\File $data_file = NULL)
    {
        parent::__construct(get_object_vars($data_file));
    }

    /**
     * Figures out if a file even exists.
     *
     * @return void
     */
    public function exists()
    {
        if ($this->get_name())
            return $this->validate_information();
        else
            return $this->proposal->submit();
    }

    /**
     * Validates the files status as an icon.
     *
     * @return void
     */
    public function validate_information()
    {
        $this->setup_validation();

        if ($this->entity_validation->check())
            return $this->upload();
        else
            throw new Exception\Validation($this->entity_validation->errors());
    }

    /**
     * Uploads the icon permanently in the server.
     *
     * @return void
     */
    public function upload()
    {
        $this->repository->save_icon($this);
        $this->resize();
    }

    public function resize()
    {
        $this->entity_image->resize(50, 50);
        $this->proposal->submit();
    }

    /**
     * Sets up the validation requirements
     *
     * @return void
     */
    private function setup_validation()
    {
        $this->entity_validation->setup(array(
            'metadata' => array(
                'name' => $this->get_name(),
                'type' => $this->get_mimetype(),
                'tmp_name' => $this->get_tmp_name(),
                'error' => $this->get_error(),
                'size' => $this->get_filesize()
            )
        ));
        $this->entity_validation->rule('metadata', 'upload_valid');
        $this->entity_validation->rule('metadata', 'upload_type', array('jpg', 'png'));
        $this->entity_validation->rule('metadata', 'upload_size', '1M');
    }
}
