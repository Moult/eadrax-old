<?php

namespace spec\Eadrax\Core\Data;

use PHPSpec2\ObjectBehavior;

class User extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_should_have_an_id()
    {
        $this->id->shouldBe(NULL);
    }

    function it_should_have_a_username()
    {
        $this->username->shouldBe(NULL);
    }

    function it_should_have_a_password()
    {
        $this->password->shouldBe(NULL);
    }

    function it_should_have_an_email()
    {
        $this->email->shouldBe(NULL);
    }
}
