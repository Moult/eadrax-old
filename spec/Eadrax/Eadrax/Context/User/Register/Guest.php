<?php

namespace spec\Eadrax\Eadrax\Context\User\Register;

require_once 'spec/Eadrax/Eadrax/Context/User/Register/Guest/Interaction.php';
require_once 'spec/Eadrax/Eadrax/Context/Interaction.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Eadrax\Context;

class Guest extends ObjectBehavior
{
    use Context\Interaction, Guest\Interaction;

    /**
     * @param Eadrax\Eadrax\Context\User\Register\Repository $repository
     * @param Eadrax\Eadrax\Entity\Auth                      $entity_auth
     * @param Eadrax\Eadrax\Entity\Validation                $entity_validation
     */
    function let($repository, $entity_auth, $entity_validation)
    {
        $data_user = new \Eadrax\Eadrax\Data\User;
        $data_user->username = 'username';
        $this->beConstructedWith($data_user, $repository, $entity_auth, $entity_validation);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\User\Register\Guest');
    }

    function it_is_a_guest_role()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Data\User');
        $this->shouldHaveType('Eadrax\Eadrax\Context\User\Register\Guest\Requirement');
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
        $this->repository->shouldHaveType('Eadrax\Eadrax\Context\User\Register\Repository');
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
