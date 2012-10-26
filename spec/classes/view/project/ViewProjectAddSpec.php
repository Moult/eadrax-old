<?php

class DescribeViewProjectAdd extends \PHPSpec\Context
{
    public function before()
    {
        $this->_subject = new View_Project_Add;
    }

    public function itShouldUseKostache()
    {
        $this->spec($this->_subject)->should->beAnInstanceOf('View_Layout');
    }
}
