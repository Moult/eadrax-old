<?php

namespace spec\Eadrax\Core\Usecase\Update;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DeleteSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Update $update
     * @param Eadrax\Core\Usecase\Update\Delete\Repository $update_delete
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($update, $update_delete, $authenticator)
    {
        $data = array(
            'update' => $update
        );

        $repositories = array(
            'update_delete' => $update_delete
        );

        $tools = array(
            'authenticator' => $authenticator
        );

        $this->beConstructedWith($data, $repositories, $tools);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Delete');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Update\Delete\Interactor');
    }
}
