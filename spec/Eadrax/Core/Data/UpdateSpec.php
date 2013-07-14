<?php

namespace spec\Eadrax\Core\Data;

use PhpSpec\ObjectBehavior;

class UpdateSpec extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Update');
    }

    function it_has_an_id()
    {
        $this->id->shouldBe(NULL);
    }

    function it_has_a_private_option()
    {
        $this->private->shouldBe(NULL);
    }

    function it_belongs_to_a_project()
    {
        $this->project->shouldBe(NULL);
    }
}
