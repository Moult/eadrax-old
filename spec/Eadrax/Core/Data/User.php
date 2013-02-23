<?php

namespace spec\Eadrax\Core\Data;

use PHPSpec2\ObjectBehavior;

class User extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_should_have_an_id_attribute()
    {
        $this->id->shouldBe(NULL);
    }

    function it_should_have_a_username_attribute()
    {
        $this->username->shouldBe(NULL);
    }

    function it_should_have_a_password_attribute()
    {
        $this->password->shouldBe(NULL);
    }

    function it_should_have_an_email_attribute()
    {
        $this->email->shouldBe(NULL);
    }
}
