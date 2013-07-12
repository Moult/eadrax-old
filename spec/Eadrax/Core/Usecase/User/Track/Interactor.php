<?php

namespace spec\Eadrax\Core\Usecase\User\Track;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\User\Track\Idol $idol
     * @param Eadrax\Core\Usecase\User\Track\Fan $fan
     */
    function let($idol, $fan)
    {
        $this->beConstructedWith($idol, $fan);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Track\Interactor');
    }

    function it_tracks_the_idol($idol, $fan)
    {
        $fan->get_id()->willReturn('fan_id');
        $idol->get_id()->willReturn('idol_id');
        $fan->authorise()->shouldBeCalled();
        $fan->has_idol('idol_id')->shouldBeCalled()->willReturn(FALSE);
        $fan->remove_tracked_projects_by('idol_id')->shouldBeCalled();
        $fan->add_idol('idol_id')->shouldBeCalled();
        $idol->notify_new_fan('fan_id')->shouldBeCalled();
        $this->interact();
    }

    function it_does_nothing_if_fan_already_has_idol($idol, $fan)
    {
        $idol->get_id()->willReturn('idol_id');
        $fan->authorise()->shouldBeCalled();
        $fan->has_idol('idol_id')->shouldBeCalled()->willReturn(TRUE);
        $fan->add_idol('idol_id')->shouldNotBeCalled();
        $this->interact();
    }
}
