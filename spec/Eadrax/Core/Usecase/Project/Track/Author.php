<?php

namespace spec\Eadrax\Core\Usecase\Project\Track;

use PHPSpec2\ObjectBehavior;

class Author extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Project\Track\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Emailer $emailer
     * @param Eadrax\Core\Tool\Formatter $formatter
     * @param Eadrax\Core\Data\User $fan
     */
    function let($project, $repository, $authenticator, $emailer, $formatter, $fan)
    {
        $project->id = 'project_id';
        $repository->get_project_author_id_and_username('project_id')->shouldBeCalled()->willReturn(array('author_id', 'author_username'));
        $this->beConstructedWith($project, $repository, $authenticator, $emailer, $formatter);
        $fan->id = 'fan_id';
        $fan->username = 'fan_username';
        $authenticator->get_user()->willReturn($fan);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Track\Author');
    }

    function it_should_be_a_user()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_should_get_the_id()
    {
        $this->get_id()->shouldReturn('author_id');
    }

    function it_can_remove_fan_from_all_projects($repository)
    {
        $repository->delete_fan_from_projects_owned_by_user('fan_id', 'author_id')->shouldBeCalled();
        $this->remove_fan_from_all_projects();
    }

    function it_can_notify_about_new_project_tracker($repository, $emailer, $formatter)
    {
        $repository->get_project_name_and_author_email('project_id')->shouldBeCalled()->willReturn(array('project_name', 'author_email'));
        $formatter->setup(array(
            'author_username' => 'author_username',
            'fan_username' => 'fan_username',
            'fan_id' => 'fan_id',
            'project_name' => 'project_name',
            'project_id' => 'project_id'
        ))->shouldBeCalled();
        $formatter->format('email_project_track_body')->shouldBeCalled()->willReturn('email_body');
        $formatter->setup(array(
            'fan_username' => 'fan_username',
            'project_name' => 'project_name'
        ))->shouldBeCalled();
        $formatter->format('email_project_track_subject')->shouldBeCalled()->willReturn('email_subject');
        $emailer->set_to('author_email')->shouldBeCalled();
        $emailer->set_subject('email_subject')->shouldBeCalled();
        $emailer->set_body('email_body')->shouldBeCalled();
        $emailer->send()->shouldBeCalled();
        $this->notify_about_new_project_tracker('project_id');
    }

    function it_can_get_number_of_projects_owned_by_author($repository)
    {
        $repository->get_number_of_projects_owned_by_author('author_id')->shouldBeCalled()->willReturn(42);
        $this->get_number_of_projects()->shouldReturn(42);
    }
}
