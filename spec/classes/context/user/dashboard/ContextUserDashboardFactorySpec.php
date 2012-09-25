<?php

class DescribeContextUserDashboardFactory extends \PHPSpec\Context
{
    public function itIsAFactory()
    {
        $factory = Mockery::mock('Context_User_Dashboard_Factory');
        $this->spec($factory)->should->beAnInstanceOf('Context_Factory');
    }

    public function itShouldCreateAContext()
    {
        $factory = Mockery::mock('Context_User_Dashboard_Factory[model_user,module_auth]');

        $factory->shouldReceive('model_user')->andReturn(Mockery::mock('Model_User'))->once();
        $factory->shouldReceive('module_auth')->andReturn(Mockery::mock('Auth'))->once();

        $context = $factory->fetch();
        $this->spec($context)->should->beAnInstanceOf('Context_User_Dashboard');
    }
}
