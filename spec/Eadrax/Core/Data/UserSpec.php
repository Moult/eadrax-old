<?php

namespace spec\Eadrax\Core\Data;

use PhpSpec\ObjectBehavior;

class UserSpec extends ObjectBehavior
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

    function it_should_have_a_password_verify()
    {
        $this->password_verify->shouldBe(NULL);
    }

    function it_should_have_a_bio()
    {
        $this->bio->shouldBe(NULL);
    }

    function it_should_have_a_website()
    {
        $this->website->shouldBe(NULL);
    }

    function it_should_have_a_location()
    {
        $this->location->shouldBe(NULL);
    }

    function it_should_have_an_avatar()
    {
        $this->avatar->shouldBe(NULL);
    }

    function it_should_have_a_dob()
    {
        $this->dob->shouldBe(NULL);
    }

    function it_should_have_a_gender()
    {
        $this->gender->shouldBe(NULL);
    }

    function it_should_have_a_show_email()
    {
        $this->show_email->shouldBe(NULL);
    }

    function it_should_have_a_receive_notifications()
    {
        $this->receive_notifications->shouldBe(NULL);
    }
}
