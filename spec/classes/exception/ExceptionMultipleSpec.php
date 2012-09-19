<?php

class DescribeExceptionMultiple extends \PHPSpec\Context
{
    public function before()
    {
        $this->_subject = new Exception_Multiple(array('foo'));
    }

    public function itIsAnException()
    {
        $this->spec($this->_subject)->should->beAnInstanceOf('Exception');
    }

    public function itShouldReturnConstructedArrays()
    {
        $this->spec($this->_subject->as_array())->should->be(array('foo'));
    }
}
