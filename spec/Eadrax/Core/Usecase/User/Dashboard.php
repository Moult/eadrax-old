<?php

namespace spec\Eadrax\Core\Usecase\User;

require_once 'spec/Eadrax/Core/Usecase/Core.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Usecase\Core;

class Dashboard extends ObjectBehavior
{
    use Core;

    /**
     * @param Eadrax\Core\Data\User   $data_user
     * @param Eadrax\Core\Tool\Auth $entity_auth
     */
    function let($data_user, $entity_auth)
    {
        $this->beConstructedWith($data_user, $entity_auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Dashboard');
    }

    function it_should_be_a_context()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Core');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\User\Dashboard\Interactor');
    }
}
