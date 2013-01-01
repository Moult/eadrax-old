<?php

namespace spec\Eadrax\Core\Context\User;

require_once 'spec/Eadrax/Core/Context/Core.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context\Core;

class Dashboard extends ObjectBehavior
{
    use Core;

    /**
     * @param Eadrax\Core\Data\User   $data_user
     * @param Eadrax\Core\Entity\Auth $entity_auth
     */
    function let($data_user, $entity_auth)
    {
        $this->beConstructedWith($data_user, $entity_auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\User\Dashboard');
    }

    function it_should_be_a_context()
    {
        $this->shouldHaveType('Eadrax\Core\Context\Core');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Context\User\Dashboard\Interactor');
    }
}
