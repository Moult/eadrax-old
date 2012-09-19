<?php

class DescribeModelUser extends \PHPSpec\Context
{
    public function before()
    {
        $this->_subject = new Model_User(array(
            'username' => 'username',
            'password' => 'password'
        ));
    }

    public function itShouldBeAModel()
    {
        $this->spec($this->_subject)->should->beAnInstanceOf('Model_Core');
    }

    public function itShouldHaveNecessaryAttributes()
    {
        $this->spec($this->_subject->username)->should->beSet();
        $this->spec($this->_subject->password)->should->beSet();
        $this->spec($this->_subject->email)->should->beSet();
    }

    public function itShouldConstructAllAttributes()
    {
        $this->spec($this->_subject->username)->should->be('username');
        $this->spec($this->_subject->password)->should->be('password');
        $this->spec($this->_subject->email)->should->beEmpty();
    }

    public function itHasAllNecessaryGettersAndSetters()
    {
        $this->spec(method_exists($this->_subject, 'get_username'))->should->beTrue();
        $this->spec(method_exists($this->_subject, 'set_username'))->should->beTrue();
        $this->spec(method_exists($this->_subject, 'get_password'))->should->beTrue();
        $this->spec(method_exists($this->_subject, 'set_password'))->should->beTrue();
        $this->spec(method_exists($this->_subject, 'get_email'))->should->beTrue();
        $this->spec(method_exists($this->_subject, 'set_email'))->should->beTrue();
    }
}
