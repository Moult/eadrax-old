<?php

namespace spec\Eadrax\Eadrax\Context\User\Logout;

require_once 'spec/Eadrax/Eadrax/Context/Factory.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Eadrax\Context;

class Factory extends ObjectBehavior
{
    use Context\Factory;

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\User\Logout\Factory');
    }

    function it_is_a_factory()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\Factory');
    }

    function it_finds_an_entity_auth()
    {
        $this->entity_auth()->shouldHaveType('Eadrax\Eadrax\Entity\Auth');
    }

    function it_fetches_the_context()
    {
        $this->fetch()->shouldHaveType('Eadrax\Eadrax\Context\User\Logout');
    }
}
