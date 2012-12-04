<?php

namespace spec\Eadrax\Core\Data;

require_once 'spec/Eadrax/Core/Data/Core.php';

use PHPSpec2\ObjectBehavior;

class File extends ObjectBehavior
{
    use Core;

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Data\File');
    }

    function it_should_have_a_name_attribute()
    {
        $this->set_name('foo');
        $this->get_name()->shouldBe('foo');
    }

    function it_should_have_an_tmp_name_attribute()
    {
        $this->set_tmp_name('foo');
        $this->get_tmp_name()->shouldBe('foo');
    }

    function it_should_have_a_mimetype_attribute()
    {
        $this->set_mimetype('foo');
        $this->get_mimetype()->shouldBe('foo');
    }

    function it_should_have_a_filesize_attribute()
    {
        $this->set_filesize(42);
        $this->get_filesize()->shouldBe(42);
        $this->set_filesize('foo');
        $this->get_filesize()->shouldBe(0);
        $this->set_filesize(3.14);
        $this->get_filesize()->shouldBe(3);
    }

    function it_should_have_an_error_attribute()
    {
        $this->set_error(42);
        $this->get_error()->shouldBe(42);
        $this->set_error('foo');
        $this->get_error()->shouldBe(0);
        $this->set_error(3.14);
        $this->get_error()->shouldBe(3);
    }
}
