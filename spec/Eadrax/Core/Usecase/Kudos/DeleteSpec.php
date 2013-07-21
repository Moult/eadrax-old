<?php

namespace spec\Eadrax\Core\Usecase\Kudos;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DeleteSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Update $update
     * @param Eadrax\Core\Usecase\Kudos\Delete\Repository $kudos_delete
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($update, $kudos_delete, $authenticator)
    {
        $data = array(
            'update' => $update
        );

        $repositories = array(
            'kudos_delete' => $kudos_delete
        );

        $tools = array(
            'authenticator' => $authenticator
        );

        $this->beConstructedWith($data, $repositories, $tools);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Kudos\Delete');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Kudos\Delete\Interactor');
    }
}
