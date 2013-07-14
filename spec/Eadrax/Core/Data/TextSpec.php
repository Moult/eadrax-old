<?php

namespace spec\Eadrax\Core\Data;

use PhpSpec\ObjectBehavior;

class TextSpec extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Text');
    }

    function it_should_be_an_update()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Update');
    }

    function it_should_have_a_message()
    {
        $this->message->shouldBe(NULL);
    }
}
