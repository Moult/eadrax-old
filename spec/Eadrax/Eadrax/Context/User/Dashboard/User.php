<?php

namespace spec\Eadrax\Eadrax\Context\User\Dashboard;

use PHPSpec2\ObjectBehavior;

class User extends ObjectBehavior
{
    function let($model_user)
    {
        $model_user = new \Eadrax\Eadrax\Model\User;
        $model_user->username = 'username';
        $this->beConstructedWith($model_user);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\User\Dashboard\User');
    }

    function it_is_a_user_role()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Model\User');
        $this->shouldHaveType('Eadrax\Eadrax\Context\User\Dashboard\User\Requirement');
    }

    function it_should_be_able_to_construct_data()
    {
        $this->get_username()->shouldBe('username');
        $this->get_password()->shouldBe(NULL);
        $this->get_email()->shouldBe(NULL);
        $this->get_id()->shouldBe(NULL);
    }

    function it_should_be_able_to_import_data_from_a_user_model()
    {
        $model_user = new \Eadrax\Eadrax\Model\User;
        $model_user->username = 'foo';
        $this->assign_data($model_user);
        $this->get_username()->shouldBe('foo');
    }
}
