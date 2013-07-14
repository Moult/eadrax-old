<?php

namespace spec\Eadrax\Core\Data;

use PhpSpec\ObjectBehavior;

class PasteSpec extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Paste');
    }

    function it_is_an_update()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Update');
    }

    function it_has_text()
    {
        $this->text->shouldBe(NULL);
    }

    function it_has_a_syntax()
    {
        $this->syntax->shouldBe(NULL);
    }
}
