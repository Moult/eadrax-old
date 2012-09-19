<?php

class DescribeExceptionValidation extends \PHPSpec\Context
{
    public function itSupportsMultipleExceptions()
    {
        $this->spec(new Exception_Validation(array()))->should->beAnInstanceOf('Exception_Multiple');
    }
}
