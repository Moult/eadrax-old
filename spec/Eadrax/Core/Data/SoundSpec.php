<?php

namespace spec\Eadrax\Core\Data;

use PhpSpec\ObjectBehavior;

class SoundSpec extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Sound');
    }

    function it_is_an_update()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Update');
    }

    function it_has_a_thumbnail()
    {
        $this->thumbnail->shouldBe(NULL);
    }

    function it_has_a_file()
    {
        $this->file->shouldBe(NULL);
    }

    function it_has_a_length()
    {
        $this->length->shouldBe(NULL);
    }

    function it_has_a_filesize()
    {
        $this->filesize->shouldBe(NULL);
    }
}
