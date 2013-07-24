<?php

namespace spec\Eadrax\Core\Usecase\Comment\Add;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UpdateSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Update $update
     * @param Eadrax\Core\Usecase\Comment\Add\Repository $repository
     * @param Eadrax\Core\Tool\Emailer $emailer
     * @param Eadrax\Core\Tool\Formatter $formatter
     */
    function let($update, $repository, $emailer, $formatter)
    {
        $update->id = 'id';
        $this->beConstructedWith($update, $repository, $emailer, $formatter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Comment\Add\Update');
    }

    function it_is_an_update()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Update');
    }

    function it_checks_whether_or_not_the_update_exists($repository)
    {
        $repository->does_update_exist('id')->shouldBeCalled()->willReturn(TRUE);
        $this->does_exist()->shouldReturn(TRUE);
    }

    function it_notifies_the_author_about_the_comment($repository, $emailer, $formatter)
    {
        $repository->get_update_details('id')->shouldBeCalled()->willReturn(array(
            'author_username' => 'author_username',
            'author_email' => 'author_email',
            'project_id' => 'project_id',
            'project_name' => 'project_name'
        ));
        $formatter->format(array(
            'author_username' => 'author_username',
            'update_id' => 'id',
            'project_id' => 'project_id',
            'project_name' => 'project_name',
            'comment_author_username' => 'comment_author_username',
            'comment_text' => 'comment_text'
        ))->shouldBeCalled();
        $formatter->format('Email_Comment_Add_Subject')->shouldBeCalled()->willReturn('email_subject');
        $formatter->format('Email_Comment_Add_Body')->shouldBeCalled()->willReturn('email_body');
        $emailer->set_to('author_email')->shouldBeCalled();
        $emailer->set_subject('email_subject')->shouldBeCalled();
        $emailer->set_body('email_body')->shouldBeCalled();
        $emailer->send()->shouldBeCalled();
        $this->notify_author_about_comment('comment_author_username', 'comment_text');
    }
}
