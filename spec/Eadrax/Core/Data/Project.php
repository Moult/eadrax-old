<?php

namespace spec\Eadrax\Core\Data;

require_once 'spec/Eadrax/Core/Data/Core.php';

use PHPSpec2\ObjectBehavior;

class Project extends ObjectBehavior
{
    use Core;

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    function it_should_be_a_data()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Core');
    }

    function it_should_have_a_name_attribute()
    {
        $this->set_name('foo');
        $this->get_name()->shouldBe('foo');
    }

    function it_should_have_a_summary_attribute()
    {
        $this->set_summary('foo');
        $this->get_summary()->shouldBe('foo');
    }

    /**
     * @param Eadrax\Core\Data\User $user
     */
    function it_should_have_an_author_attribute($user)
    {
        $this->set_author($user);
        $this->get_author()->shouldBe($user);

        $this->shouldThrow('PHPSpec2\Exception\Example\ErrorException')->duringSet_author('Foo'); // Not $user
    }

    function it_should_have_a_description_attribute()
    {
        $this->set_description('foo');
        $this->get_description()->shouldBe('foo');
    }

    /**
     * @param Eadrax\Core\Data\File $file
     */
    function it_should_have_an_icon_attribute($file)
    {
        $this->set_icon($file);
        $this->get_icon()->shouldBe($file);

        $this->shouldThrow('PHPSpec2\Exception\Example\ErrorException')->duringSet_icon('Foo');
    }

    function it_should_have_a_website_attribute()
    {
        $this->set_website('foo');
        $this->get_website()->shouldBe('foo');
    }

    function it_should_have_a_views_attribute()
    {
        $this->set_views(1);
        $this->get_views()->shouldBe(1);

        // Check only allows ints
        $this->set_views('Foo');
        $this->get_views()->shouldBe(0);
    }

    function it_should_have_a_last_updated_attribute()
    {
        $this->set_last_updated(time());
        $this->get_last_updated()->shouldBe(time());

        // Check only allows ints
        $this->set_last_updated('Foo');
        $this->get_last_updated()->shouldBe(0);
    }
}
