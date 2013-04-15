<?php

namespace spec\Eadrax\Core\Usecase\Update\Delete;

use PHPSpec2\ObjectBehavior;

class Project extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Data\User $author
     * @param Eadrax\Core\Tool\Auth $auth
     */
    function let($project, $author, $auth)
    {
        $project->author = $author;
        $this->beConstructedWith($project, $auth);
    }

    /**
     * @param Eadrax\Core\Data\User $author
     */
    function it_should_be_initializable($author)
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Delete\Project');
        $this->author->shouldBe($author);
    }

    function it_should_be_a_project()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    /**
     * @param Eadrax\Core\Data\User $author
     */
    function it_authorises_users_who_are_the_project_author($auth, $author)
    {
        $auth->get_user()->shouldBeCalled()->willReturn($author);
        $this->authorise();
    }

    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Data\User $author
     */
    function it_does_not_authorise_users_who_are_not_project_authors($auth, $user, $author)
    {
        $author->id = 'id';
        $user->id = 'not id';
        $auth->get_user()->shouldBeCalled()->willReturn($user);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }
}
