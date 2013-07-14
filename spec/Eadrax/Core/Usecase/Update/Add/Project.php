<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PHPSpec2\ObjectBehavior;

class Project extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Update\Add\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Emailer $emailer
     * @param Eadrax\Core\Tool\Formatter $formatter
     */
    function let($project, $repository, $authenticator, $emailer, $formatter)
    {
        $project->id = 'project_id';
        $repository->get_project_name_and_author_id_and_username('project_id')->willReturn(array('project_name', 'author_id', 'author_username'));
        $this->beConstructedWith($project, $repository, $authenticator, $emailer, $formatter);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Project');
    }

    function it_should_be_a_project()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    /**
     * @param Eadrax\Core\Data\User $logged_in_user
     */
    function it_authorises_project_authors($authenticator, $logged_in_user)
    {
        $logged_in_user->id = 'author_id';
        $authenticator->get_user()->shouldBeCalled()->willReturn($logged_in_user);
        $this->authorise_ownership();
    }

    /**
     * @param Eadrax\Core\Data\User $logged_in_user
     */
    function it_does_not_authorise_impostors($authenticator, $logged_in_user)
    {
        $logged_in_user->id = 'impostor_id';
        $authenticator->get_user()->shouldBeCalled()->willReturn($logged_in_user);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise_ownership();
    }

    function it_can_notify_trackers($repository, $emailer, $formatter)
    {
        $repository->get_id_and_username_and_email_of_all_trackers('project_id')
            ->shouldBeCalled()->willReturn(array(
            array('tracker1_id', 'tracker1_username', 'tracker1_email')
        ));

        $formatter->setup(array(
            'tracker1_id',
            'tracker1_username',
            'author_id',
            'author_username',
            'project_id',
            'project_name'
        ))->shouldBeCalled();
        $formatter->format('email_update_add_subject')->shouldBeCalled(1)->willReturn('tracker1_email_subject');
        $formatter->format('email_update_add_body')->shouldBeCalled()->willReturn('tracker1_email_body');
        $emailer->set_to('tracker1_email')->shouldBeCalled();
        $emailer->set_subject('tracker1_email_subject')->shouldBeCalled();
        $emailer->set_body('tracker1_email_body')->shouldBeCalled();
        $emailer->queue()->shouldBeCalled();

        $emailer->send_queue()->shouldBeCalled();

        $this->notify_trackers();
    }
}
