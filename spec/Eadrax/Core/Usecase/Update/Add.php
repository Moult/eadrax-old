<?php

namespace spec\Eadrax\Core\Usecase\Update;

use PHPSpec2\ObjectBehavior;

class Add extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Data\Update $update
     * @param Eadrax\Core\Usecase\Update\Add\Repository $update_add
     * @param Eadrax\Core\Tool\Auth $auth
     * @param Eadrax\Core\Tool\Filesystem $filesystem
     * @param Eadrax\Core\Tool\Image $image
     * @param Eadrax\Core\Tool\Mail $mail
     * @param Eadrax\Core\Tool\Upload $upload
     * @param Eadrax\Core\Tool\Validation $validation
     */
    function let($project, $update, $update_add, $auth, $filesystem, $image, $mail, $upload, $validation)
    {
        $data = array(
            'project' => $project,
            'update' => $update
        );

        $repositories = array(
            'update_add' => $update_add
        );

        $tools = array(
            'auth' => $auth,
            'filesystem' => $filesystem,
            'image' => $image,
            'mail' => $mail,
            'upload' => $upload,
            'validation' => $validation
        );

        $this->beConstructedWith($data, $repositories, $tools);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Interactor');
    }
}
