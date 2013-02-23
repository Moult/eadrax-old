<?php

namespace spec\Eadrax\Core\Data;

use PHPSpec2\ObjectBehavior;

class Project extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    function it_should_have_a_name_attribute()
    {
        $this->name->shouldBe(NULL);
    }

    function it_should_have_a_summary_attribute()
    {
        $this->summary->shouldBe(NULL);
    }

    function it_should_have_an_author_attribute()
    {
        $this->author->shouldBe(NULL);
    }

    function it_should_have_a_description_attribute()
    {
        $this->description->shouldBe(NULL);
    }

    function it_should_have_a_website_attribute()
    {
        $this->website->shouldBe(NULL);
    }

    function it_should_have_a_views_attribute()
    {
        $this->views->shouldBe(NULL);
    }

    function it_should_have_a_last_updated_attribute()
    {
        $this->last_updated->shouldBe(NULL);
    }
}
