<?php

class DescribeContextUserRegisterFactory extends \PHPSpec\Context
{
    public function itShouldCreateAContext()
    {
        $factory = Mockery::mock('Context_User_Register_Factory[model_user,module_auth]');

        $factory->shouldReceive('model_user')->andReturn(Mockery::mock('Model_User'))->once();
        $factory->shouldReceive('module_auth')->andReturn(Mockery::mock('Auth'))->once();

        $context = $factory->fetch();
        $this->spec($context)->should->beAnInstanceOf('Context_User_Register');
    }

    public function itShouldAutoAllocateDataToModels()
    {
        $factory = new Context_User_Register_Factory(array(
            'username' => 'username',
            'password' => 'password',
            'email'    => 'email'
        ));

        $model_user = $factory->model_user();
        $this->spec($model_user->username)->should->be('username');
        $this->spec($model_user->password)->should->be('password');
        $this->spec($model_user->email)->should->be('email');
    }
}
