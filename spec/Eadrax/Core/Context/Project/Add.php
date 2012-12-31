<?php

namespace spec\Eadrax\Core\Context\Project;

require_once 'spec/Eadrax/Core/Context/Core.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context\Core;

class Add extends ObjectBehavior
{
    use Core;

    /**
     * @param \Eadrax\Core\Data\User                          $data_user
     * @param \Eadrax\Core\Data\Project                       $data_project
     * @param \Eadrax\Core\Data\File                          $data_file
     * @param \Eadrax\Core\Context\Project\Add\Repository     $repository
     * @param \Eadrax\Core\Context\Project\Prepare\Repository $repository_project_prepare
     * @param \Eadrax\Core\Entity\Auth                        $entity_auth
     * @param \Eadrax\Core\Entity\Validation                  $entity_validation
     * @param \Eadrax\Core\Entity\Image                       $entity_image
     */
    function let($data_user, $data_project, $data_file, $repository, $repository_project_prepare, $entity_auth, $entity_validation, $entity_image)
    {
        $data_project->get_author()->willReturn($data_user);
        $data_project->get_icon()->willReturn($data_file);
        $this->beConstructedWith($data_project, $repository, $repository_project_prepare, $entity_auth, $entity_validation, $entity_image);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\Project\Add');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Context\Project\Add\Interactor');
    }
}
