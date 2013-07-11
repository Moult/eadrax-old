<?php

namespace spec\Eadrax\Core\Usecase\Project;

use PHPSpec2\ObjectBehavior;

class Track extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Project\Track\Repository $project_track
     * @param Eadrax\Core\Usecase\User\Track\Repository $user_track
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Emailer $emailer
     * @param Eadrax\Core\Tool\Formatter $formatter
     */
    function let($user, $project, $user_track, $project_track, $authenticator, $emailer, $formatter)
    {
        $project->id = 'project_id';
        $project_track->get_project_author_id_and_username('project_id')->willReturn(array('author_id', 'author_username'));
        $authenticator->get_user()->willReturn($user);

        $data = array(
            'project' => $project
        );

        $repositories = array(
            'project_track' => $project_track,
            'user_track' => $user_track
        );

        $tools = array(
            'authenticator' => $authenticator,
            'emailer' => $emailer,
            'formatter' => $formatter
        );

        $this->beConstructedWith($data, $repositories, $tools);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Track');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Project\Track\Interactor');
    }

    function it_can_execute_its_sub_usecase($user, $user_track, $authenticator)
    {
        $user_track->get_username_and_email('')->willReturn($user);
        $authenticator->get_user()->willReturn($user);
        $this->get_user_track()->fetch()->shouldHaveType('Eadrax\Core\Usecase\User\Track\Interactor');
    }
}
