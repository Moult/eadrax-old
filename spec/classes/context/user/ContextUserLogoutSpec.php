<?php

class DescribeContextUserLogout extends \PHPSpec\Context
{
    public function itShouldBeAContext()
    {
        $this->spec(Mockery::mock('Context_User_Logout'))->should->beAnInstanceOf('Context_Core');
        $this->spec(method_exists(Mockery::mock('Context_User_Logout'), 'execute'))->should->beTrue();
    }

    public function itReturnsSuccess()
    {
        $module_auth = Mockery::mock('Module_Auth');
        $module_auth->shouldReceive('logout')->andReturn(TRUE)->once();

        $context = Mockery::mock('Context_User_Logout[]');
        $context->module_auth = $module_auth;
        $result = $context->execute();

        $this->spec($result)->should->be(array(
            'status' => 'success'
        ));
    }
}
