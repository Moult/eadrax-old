<?php

namespace spec\Eadrax\Core\Data;

use PhpSpec\ObjectBehavior;

class HookSpec extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Hook');
    }

    function it_has_an_id()
    {
        $this->id->shouldBe(NULL);
    }

    function it_has_a_url()
    {
        $this->url->shouldBe(NULL);
    }
}
