<?php

namespace spec\Eadrax\Core\Usecase\User\Track;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\User\Track\Idol $idol
     * @param Eadrax\Core\Usecase\User\Track\User $user
     */
    function let($idol, $user)
    {
        $this->beConstructedWith($idol, $user);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Track\Interactor');
    }

    function it_tracks_the_idol($idol, $user)
    {
        $user->authorise()->shouldBeCalled();
        $user->has_idol($idol)->shouldBeCalled()->willReturn(FALSE);
        $user->add_idol($idol)->shouldBeCalled();
        $idol->notify()->shouldBeCalled();
        $this->interact();
    }

    function it_untracks_the_idol($idol, $user)
    {
        $user->authorise()->shouldBeCalled();
        $user->has_idol($idol)->shouldBeCalled()->willReturn(TRUE);
        $user->remove_idol($idol)->shouldBeCalled();
        $this->interact();
    }
}
