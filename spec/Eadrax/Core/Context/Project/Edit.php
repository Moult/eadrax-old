<?php

namespace spec\Eadrax\Core\Context\Project;

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context\Core;

class Edit extends ObjectBehavior
{
    use Core;

    /**
     * @param \Eadrax\Core\Data\User    $data_user
     * @param \Eadrax\Core\Data\Project $data_project
     * @param \Eadrax\Core\Entity\Auth  $entity_auth
     */
    function let($data_user, $data_project, $entity_auth)
    {
        $data_user->get_id()->willReturn(42);
        $entity_auth->get_user()->willReturn($data_user);
        $data_project->get_author()->willReturn($data_user);
        $this->beConstructedWith($data_project, $entity_auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\Project\Edit');
    }

    function it_loads_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Context\Project\Edit\Interactor');
    }
}
