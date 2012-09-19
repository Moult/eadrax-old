<?php

class DescribeContextCore extends \PHPSpec\Context
{
    public function itShouldBeAbstract()
    {
        $context_core = new ReflectionClass('Context_Core');
        $this->spec($context_core->isAbstract())->should->beTrue();
    }
}
