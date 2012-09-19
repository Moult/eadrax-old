<?php

class DescribeViewLayout extends \PHPSpec\Context
{
    public function before()
    {
        $this->_subject = Mockery::mock('View_Layout[]');
    }

    public function itShouldUseKostacheLayout()
    {
        $this->spec($this->_subject)->should->beAnInstanceOf('Kostache_Layout');
    }

    public function itShouldDefineHeaderAndFooter()
    {
        $this->spec($this->_subject->get_partials())->shouldNot->beEmpty();
    }
}
