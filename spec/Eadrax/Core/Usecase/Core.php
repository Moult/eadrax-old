<?php

namespace spec\Eadrax\Core\Usecase;

trait Core {
    function it_is_a_core_context()
    {
        $this->shouldHaveType('\Eadrax\Core\Usecase\Core');
    }
}
