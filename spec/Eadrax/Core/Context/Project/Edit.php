<?php

namespace spec\Eadrax\Core\Context\Project;

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context\Core;

class Edit extends ObjectBehavior
{
    use Core;

    /**
     * @param \Eadrax\Core\Data\User                       $data_user
     * @param \Eadrax\Core\Data\Project                    $data_project
     * @param \Eadrax\Core\Entity\Auth                     $entity_auth
     */
    function let($data_user, $data_project, $entity_auth)
    {
        $data_project->get_author()->willReturn($data_user);
        $this->beConstructedWith($data_project, $entity_auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\Project\Edit');
    }

    function it_assigns_the_proposal_role()
    {
        $this->proposal->shouldHaveType('\Eadrax\Core\Context\Project\Edit\Proposal');
        $this->proposal->shouldHaveType('\Eadrax\Core\Data\Project');
    }

    function it_assigns_the_user_role()
    {
        $this->user->shouldHaveType('\Eadrax\Core\Context\Project\Edit\User');
        $this->user->shouldHaveType('\Eadrax\Core\Data\User');
        $this->user->proposal->shouldHaveType('\Eadrax\Core\Context\Project\Edit\Proposal');
        $this->user->proposal->shouldHaveType('\Eadrax\Core\Data\Project');
    }

    function it_should_catch_authentication_errors_during_usecase_execution($data_project, $entity_auth)
    {
        $entity_auth->logged_in()->willReturn(FALSE);
        $this->beConstructedWith($data_project, $entity_auth);
        $this->execute()->shouldReturn(array(
            'status' => 'failure',
            'type' => 'authorisation',
            'data' => array(
                'errors' => array('You need to be logged in to edit a project.')
            )
        ));
    }
}
