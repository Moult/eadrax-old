<?php

class DescribeModelCore extends \PHPSpec\Context
{
    public function before()
    {
        $this->_subject = Mockery::mock('Model_Core[]');
    }

    public function itShouldBeAModel()
    {
        $this->spec($this->_subject)->should->beAnInstanceOf('Model');
    }
}
