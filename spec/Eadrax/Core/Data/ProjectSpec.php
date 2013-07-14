<?php

namespace spec\Eadrax\Core\Data;

use PhpSpec\ObjectBehavior;

class ProjectSpec extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    function it_should_have_an_id()
    {
        $this->id->shouldBe(NULL);
    }

    function it_should_have_a_name()
    {
        $this->name->shouldBe(NULL);
    }

    function it_should_have_a_summary()
    {
        $this->summary->shouldBe(NULL);
    }

    function it_should_have_an_author()
    {
        $this->author->shouldBe(NULL);
    }

    function it_should_have_a_description()
    {
        $this->description->shouldBe(NULL);
    }

    function it_should_have_a_website()
    {
        $this->website->shouldBe(NULL);
    }

    function it_should_have_a_views()
    {
        $this->views->shouldBe(NULL);
    }

    function it_should_have_a_last_updated()
    {
        $this->last_updated->shouldBe(NULL);
    }
}
