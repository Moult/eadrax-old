<?php

namespace spec\Eadrax\Core\Usecase\Project;

use PHPSpec2\ObjectBehavior;

class Track extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Project\Track\Repository $project_track
     * @param Eadrax\Core\Usecase\User\Track\Repository $user_track
     * @param Eadrax\Core\Tool\Auth $auth
     * @param Eadrax\Core\Tool\Mail $mail
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Data\User $author
     */
    function let($project_track, $user_track, $auth, $mail, $user, $project, $author)
    {
        $user->id = 'id';
        $auth->get_user()->willReturn($user);
        $author->id = 'id';
        $user_track->get_username_and_email('id')->willReturn($user);
        $project_track->get_project_author($project)->shouldBeCalled()->willReturn($author);

        $data = array(
            'project' => $project
        );

        $repositories = array(
            'project_track' => $project_track,
            'user_track' => $user_track
        );

        $tools = array(
            'auth' => $auth,
            'mail' => $mail
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
}
