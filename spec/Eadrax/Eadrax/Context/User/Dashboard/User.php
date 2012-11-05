<?php

namespace spec\Eadrax\Eadrax\Context\User\Dashboard;

require_once 'spec/Eadrax/Eadrax/Context/User/Dashboard/User/Interaction.php';
require_once 'spec/Eadrax/Eadrax/Context/Interaction.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Eadrax\Context;

class User extends ObjectBehavior
{
    use Context\Interaction, User\Interaction;

    /**
     * @param Eadrax\Eadrax\Entity\Auth $entity_auth
     */
    function let($entity_auth)
    {
        $data_user = new \Eadrax\Eadrax\Data\User;
        $data_user->username = 'username';
        $this->beConstructedWith($data_user, $entity_auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\User\Dashboard\User');
    }

    function it_is_a_user_role()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Data\User');
        $this->shouldHaveType('Eadrax\Eadrax\Context\User\Dashboard\User\Requirement');
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
        $this->entity_auth->shouldHaveType('Eadrax\Eadrax\Entity\Auth');
    }

    function it_should_be_able_to_import_data_from_a_user_data()
    {
        $data_user = new \Eadrax\Eadrax\Data\User;
        $data_user->username = 'foo';
        $this->assign_data($data_user);
        $this->get_username()->shouldBe('foo');
    }
}
