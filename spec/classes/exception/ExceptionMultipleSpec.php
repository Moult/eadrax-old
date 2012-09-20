<?php

class DescribeExceptionMultiple extends \PHPSpec\Context
{
    public function before()
    {
        $this->_subject = new Exception_Multiple(array('foo' => 'bar'));
    }

    public function itIsAnException()
    {
        $this->spec($this->_subject)->should->beAnInstanceOf('Exception');
    }

    public function itShouldReturnConstructedArrays()
    {
        $this->spec($this->_subject->as_array())->should->be(array('foo' => 'bar'));
    }

    public function itShouldGenerateAStringOfErrors()
    {
        $this->spec($this->_subject->getMessage())->should->be('Multiple exceptions thrown: foo');
    }
}
