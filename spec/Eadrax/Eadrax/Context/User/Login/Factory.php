<?php

namespace spec\Eadrax\Eadrax\Context\User\Login;

require_once 'spec/Eadrax/Eadrax/Context/Factory.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Eadrax\Context;

class Factory extends ObjectBehavior
{
    use Context\Factory;

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\User\Login\Factory');
    }

    function it_is_a_factory()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\Factory');
    }

    function it_finds_a_model_user()
    {
        $this->model_user()->shouldHaveType('Eadrax\Eadrax\Model\User');
    }

    function it_finds_an_entity_auth()
    {
        $this->entity_auth()->shouldHaveType('Eadrax\Eadrax\Entity\Auth');
    }

    function it_finds_an_entity_validation()
    {
        $this->entity_validation()->shouldHaveType('Eadrax\Eadrax\Entity\Validation');
    }

    function it_finds_a_role_guest()
    {
        $this->role_guest()->shouldHaveType('Eadrax\Eadrax\Context\User\Login\Guest');
    }

    function it_finds_a_repository()
    {
        $this->repository()->shouldHaveType('Eadrax\Eadrax\Context\User\Login\Repository');
    }

    function it_fetches_the_context()
    {
        $this->fetch()->shouldHaveType('Eadrax\Eadrax\Context\User\Login');
    }
}
