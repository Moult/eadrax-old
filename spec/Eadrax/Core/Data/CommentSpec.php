<?php

namespace spec\Eadrax\Core\Data;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommentSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Comment');
    }

    function it_has_an_id()
    {
        $this->id->shouldBe(NULL);
    }

    function it_has_an_author()
    {
        $this->author->shouldBe(NULL);
    }

    function it_an_update()
    {
        $this->update->shouldBe(NULL);
    }

    function it_has_text()
    {
        $this->text->shouldBe(NULL);
    }
}
