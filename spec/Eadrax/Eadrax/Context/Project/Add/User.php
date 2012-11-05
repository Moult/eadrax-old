<?php

namespace spec\Eadrax\Eadrax\Context\Project\Add;

require_once 'spec/Eadrax/Eadrax/Context/Interaction.php';
require_once 'spec/Eadrax/Eadrax/Context/Project/Add/User/Interaction.php';

use PHPSpec2\ObjectBehavior;
use Eadrax\Eadrax\Context;

class User extends ObjectBehavior
{
    use Context\Interaction, User\Interaction;

    /**
     * @param Eadrax\Eadrax\Entity\Auth $entity_auth
     */
    function let($entity_auth)
    {
        $model_user = new \Eadrax\Eadrax\Model\User;
        $model_user->id = 'foo';
        $this->beConstructedWith($model_user, $entity_auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\Project\Add\User');
    }

    function it_is_a_user_role()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Model\User');
        $this->shouldHaveType('Eadrax\Eadrax\Context\Project\Add\User\Requirement');
    }

    function it_should_be_able_to_construct_data()
    {
        $this->get_username()->shouldBe(NULL);
        $this->get_password()->shouldBe(NULL);
        $this->get_email()->shouldBe(NULL);
        $this->get_id()->shouldBe('foo');
    }

    function it_should_construct_links()
    {
        $this->entity_auth->shouldHaveType('Eadrax\Eadrax\Entity\Auth');
    }

    function it_should_be_able_to_import_data_from_a_user_model()
    {
        $model_user = new \Eadrax\Eadrax\Model\User;
        $model_user->id = 'bar';
        $this->assign_data($model_user);
        $this->get_id()->shouldBe('bar');
    }

}
