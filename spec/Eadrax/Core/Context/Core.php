<?php

namespace spec\Eadrax\Core\Context;

trait Core {
    function it_is_a_core_context()
    {
        $this->shouldHaveType('\Eadrax\Core\Context\Core');
    }
}
