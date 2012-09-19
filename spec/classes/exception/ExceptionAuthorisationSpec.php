<?php

class DescribeExceptionAuthorisation extends \PHPSpec\Context
{
    public function itIsAnException()
    {
        $this->spec(new Exception_Authorisation)->should->beAnInstanceOf('Exception');
    }
}
