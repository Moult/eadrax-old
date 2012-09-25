<?php

class DescribeContextUserLogoutFactory extends \PHPSpec\Context
{
    public function itIsAFactory()
    {
        $factory = Mockery::mock('Context_User_Logout_Factory');
        $this->spec($factory)->should->beAnInstanceOf('Context_Factory');
    }

    public function itShouldCreateAContext()
    {
        $factory = Mockery::mock('Context_User_Logout_Factory[module_auth]');
        $factory->shouldReceive('module_auth')->andReturn(Mockery::mock('Auth'))->once();

        $context = $factory->fetch();
        $this->spec($context)->should->beAnInstanceOf('Context_User_Logout');
    }
}
