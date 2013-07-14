<?php

namespace spec\Eadrax\Core\Usecase\Kudos;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AddSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Update $update
     * @param Eadrax\Core\Usecase\Kudos\Add\Repository $kudos_add
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Emailer $emailer
     * @param Eadrax\Core\Tool\Formatter $formatter
     */
    function let($update, $kudos_add, $kudos_add, $authenticator, $emailer, $formatter)
    {
        $data = array(
            'update' => $update
        );

        $repositories = array(
            'kudos_add' => $kudos_add
        );

        $tools = array(
            'authenticator' => $authenticator,
            'emailer' => $emailer,
            'formatter' => $formatter
        );

        $this->beConstructedWith($data, $repositories, $tools);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Kudos\Add');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Kudos\Add\Interactor');
    }
}
