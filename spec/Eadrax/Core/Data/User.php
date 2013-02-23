<?php

namespace spec\Eadrax\Core\Data;

use PHPSpec2\ObjectBehavior;

class User extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_should_have_a_username_attribute()
    {
        $this->set_username('foo');
        $this->get_username()->shouldBe('foo');
    }

    function it_should_have_a_password_attribute()
    {
        $this->set_password('foo');
        $this->get_password()->shouldBe('foo');
    }

    function it_should_have_an_email_attribute()
    {
        $this->set_email('foo');
        $this->get_email()->shouldBe('foo');
    }
}
