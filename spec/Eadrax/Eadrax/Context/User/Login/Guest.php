<?php

namespace spec\Eadrax\Eadrax\Context\User\Login;

require_once 'spec/Eadrax/Eadrax/Context/User/Login/Guest/Interaction.php';
require_once 'spec/Eadrax/Eadrax/Context/Interaction.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Eadrax\Context;

class Guest extends ObjectBehavior
{
    use Context\Interaction, Guest\Interaction;

    /**
     * @param Eadrax\Eadrax\Context\User\Login\Repository $repository
     * @param Eadrax\Eadrax\Entity\Auth                   $entity_auth
     */
    function let($repository, $entity_auth)
    {
        $model_user = new \Eadrax\Eadrax\Model\User;
        $model_user->username = 'username';
        $this->beConstructedWith($model_user, $repository, $entity_auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\User\Login\Guest');
    }

    function it_is_a_guest_role()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Model\User');
        $this->shouldHaveType('Eadrax\Eadrax\Context\User\Login\Guest\Requirement');
    }

    function it_should_be_able_to_construct_data()
    {
        $this->get_username()->shouldBe('username');
        $this->get_password()->shouldBe(NULL);
        $this->get_email()->shouldBe(NULL);
        $this->get_id()->shouldBe(NULL);
    }

    function it_should_construct_links()
    {
        $this->repository->shouldHaveType('Eadrax\Eadrax\Context\User\Login\Repository');
        $this->entity_auth->shouldHaveType('Eadrax\Eadrax\Entity\Auth');
    }

    function it_should_be_able_to_import_data_from_a_user_model()
    {
        $model_user = new \Eadrax\Eadrax\Model\User;
        $model_user->username = 'foo';
        $this->assign_data($model_user);
        $this->get_username()->shouldBe('foo');
    }

}
