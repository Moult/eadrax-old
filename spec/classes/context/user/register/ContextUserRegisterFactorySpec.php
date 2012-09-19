<?php

class DescribeContextUserRegisterFactory extends \PHPSpec\Context
{
    public function itShouldCreateAContext()
    {
        $factory = Mockery::mock('Context_User_Register_Factory[model_user,module_auth]');

        $factory->shouldReceive('model_user')->andReturn(Mockery::mock('Model_User'))->once();
        $factory->shouldReceive('module_auth')->andReturn(Mockery::mock('Auth'))->once();

        $this->spec(method_exists($factory, 'fetch'))->should->beTrue();

        $context = $factory->fetch();
        $this->spec($context)->should->beAnInstanceOf('Context_User_Register');
    }
}
