<?php

class DescribeViewUserRegister extends \PHPSpec\Context
{
    public function before()
    {
        $this->_subject = new View_User_Register;
    }

    public function itShouldUseKostache()
    {
        $this->spec($this->_subject)->should->beAnInstanceOf('View_Layout');
    }
}
