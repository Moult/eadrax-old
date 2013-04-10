<?php

namespace spec\Eadrax\Core\Data;

use PHPSpec2\ObjectBehavior;

class Hook extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Hook');
    }

    function it_has_a_url()
    {
        $this->url->shouldBe(NULL);
    }
}
