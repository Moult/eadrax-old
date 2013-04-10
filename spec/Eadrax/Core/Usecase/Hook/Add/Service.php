<?php

namespace spec\Eadrax\Core\Usecase\Hook\Add;

use PHPSpec2\ObjectBehavior;

class Service extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Hook $hook
     * @param Eadrax\Core\Tool\Validation $validation
     */
    function let($hook, $validation)
    {
        $hook->url = 'hook_url';
        $this->beConstructedWith($hook, $validation);
        $this->url->shouldBe('hook_url');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Hook\Add\Service');
    }

    function it_should_be_a_hook()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Hook');
    }

    function it_should_validate_whether_it_is_valid($validation)
    {
        $validation->setup(array('url' => 'hook_url'))->shouldBeCalled();
        $validation->rule('url', 'rss2')->shouldBeCalled();
        $validation->check()->shouldBeCalled()->willReturn(TRUE);
        $this->is_valid();
    }

    function it_invalidates_fake_hooks($validation)
    {
        $validation->check()->shouldBeCalled()->willReturn(FALSE);
        $validation->errors()->shouldBeCalled()->willReturn(array('url'));
        $this->shouldThrow('Eadrax\Core\Exception\Validation')
            ->duringIs_valid();
    }
}
