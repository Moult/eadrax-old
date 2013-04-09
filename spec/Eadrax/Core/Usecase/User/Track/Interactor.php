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
        $fan->authorise()->shouldBeCalled();
        $fan->has_idol($idol)->shouldBeCalled()->willReturn(FALSE);
        $fan->remove_tracked_projects_by($idol)->shouldBeCalled();
        $fan->add_idol($idol)->shouldBeCalled();
        $idol->notify_new_fan($fan)->shouldBeCalled();
        $this->interact();
    }

    function it_untracks_the_idol($idol, $fan)
    {
        $fan->authorise()->shouldBeCalled();
        $fan->has_idol($idol)->shouldBeCalled()->willReturn(TRUE);
        $fan->remove_idol($idol)->shouldBeCalled();
        $this->interact();
    }
}
