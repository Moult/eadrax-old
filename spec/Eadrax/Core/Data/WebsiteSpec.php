<?php

namespace spec\Eadrax\Core\Data;

use PhpSpec\ObjectBehavior;

class WebsiteSpec extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Website');
    }

    function it_is_an_update()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Update');
    }

    function it_has_a_thumbnail()
    {
        $this->thumbnail->shouldBe(NULL);
    }

    function it_has_a_url()
    {
        $this->url->shouldBe(NULL);
    }
}
