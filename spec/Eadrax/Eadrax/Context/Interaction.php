<?php

namespace spec\Eadrax\Eadrax\Context;

trait Interaction
{
    function it_should_be_able_to_load_other_objects()
    {
        $this->link(array('foo' => 'bar'));
        $this->foo->shouldBe('bar');
    }
}
