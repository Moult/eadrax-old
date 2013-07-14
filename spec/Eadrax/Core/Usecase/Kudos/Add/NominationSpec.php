<?php

namespace spec\Eadrax\Core\Usecase\Kudos\Add;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NominationSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Update $update
     * @param Eadrax\Core\Data\User $logged_in_user
     * @param Eadrax\Core\Usecase\Kudos\Add\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Emailer $emailer
     * @param Eadrax\Core\Tool\Formatter $formatter
     */
    function let($update, $logged_in_user, $repository, $authenticator, $emailer, $formatter)
    {
        $update->id = 'update_id';
        $logged_in_user->id = 'user_id';
        $logged_in_user->username = 'user_username';
        $authenticator->get_user()->willReturn($logged_in_user);
        $this->beConstructedWith($update, $repository, $authenticator, $emailer, $formatter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Kudos\Add\Nomination');
    }

    function it_should_be_an_update()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Update');
    }

    function it_needs_to_check_whether_or_not_a_kudos_exists($repository)
    {
        $repository->does_update_have_kudos('update_id', 'user_id')->shouldBeCalled()->willReturn(TRUE);
        $this->has_kudos()->shouldReturn(TRUE);
    }

    function it_needs_to_add_a_kudos($repository)
    {
        $repository->add_kudos_to_update('user_id', 'update_id')->shouldBeCalled();
        $this->add_kudos();
    }

    function it_can_notify_the_author($repository, $emailer, $formatter)
    {
        $repository->get_project_id_and_name_and_author_id_and_name_and_email('update_id')->shouldBeCalled()->willReturn(array('project_id', 'project_name', 'author_id', 'author_username', 'author_email'));
        $formatter->setup(array(
            'project_id' => 'project_id',
            'project_name' => 'project_name',
            'author_id' => 'author_id',
            'author_username' => 'author_username',
            'fan_id' => 'user_id',
            'fan_username' => 'user_username'
        ))->shouldBeCalled();
        $formatter->format('email_kudos_add_subject')->shouldBeCalled()->willReturn('email_subject');
        $formatter->format('email_kudos_add_body')->shouldBeCalled()->willReturn('email_body');
        $emailer->set_to('author_email')->shouldBeCalled();
        $emailer->set_subject('email_subject')->shouldBeCalled();
        $emailer->set_body('email_body')->shouldBeCalled();
        $emailer->send()->shouldBeCalled();
        $this->notify_author();
    }
}
