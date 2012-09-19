<?php

class DescribeViewWelcomeHomepage extends \PHPSpec\Context
{
    public function before()
    {
        $this->_subject = new View_Welcome_Homepage;
    }

    public function itShouldUseOurLayout()
    {
        $this->spec($this->_subject)->should->beAnInstanceOf('View_Layout');
    }
}
