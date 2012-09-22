<?php

class DescribeContextFactory extends \PHPSpec\Context
{
    public function itShouldBeAbstract()
    {
        $context_factory = new ReflectionClass('Context_Factory');
        $this->spec($context_factory->isAbstract())->should->beTrue();
    }
}
