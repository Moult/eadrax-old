<?php

namespace spec\Eadrax\Core\Usecase\Hook\Add;

use PHPSpec2\ObjectBehavior;

class Service extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Hook $hook
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($hook, $validator)
    {
        $hook->url = 'hook_url';
        $this->beConstructedWith($hook, $validator);
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

    function it_should_validate_whether_it_is_valid($validator)
    {
        $validator->setup(array('url' => 'hook_url'))->shouldBeCalled();
        $validator->rule('url', 'rss2')->shouldBeCalled();
        $validator->check()->shouldBeCalled()->willReturn(TRUE);
        $this->is_valid();
    }

    function it_invalidates_fake_hooks($validator)
    {
        $validator->check()->shouldBeCalled()->willReturn(FALSE);
        $validator->errors()->shouldBeCalled()->willReturn(array('url'));
        $this->shouldThrow('Eadrax\Core\Exception\Validation')
            ->duringIs_valid();
    }
}
