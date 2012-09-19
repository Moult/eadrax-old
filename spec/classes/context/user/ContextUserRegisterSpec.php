<?php

class DescribeContextUserRegister extends \PHPSpec\Context
{
    public function itShouldBeAContext()
    {
        $this->spec(Mockery::mock('Context_User_Register'))->should->beAnInstanceOf('Context_Core');
        $this->spec(method_exists(Mockery::mock('Context_User_Register'), 'execute'))->should->beTrue();
    }

    public function itCatchesAuthorisationExceptionsDuringUseCaseExecution()
    {
        $model_user = new Model_User;
        $module_auth = Mockery::mock('Module_Auth');
        $module_auth->shouldReceive('logged_in')->andReturn('Username')->once();

        $context = new Context_User_Register($model_user, $module_auth);
        $result = $context->execute();

        $this->spec($result['status'])->should->be('failure');
        $this->spec($result['type'])->should->be('authorisation');
    }

    public function itCatchesValidationExceptionsDuringUseCaseExecution()
    {
        $model_user = new Model_User(array(
            'username' => 'bad username',
            'password' => 'short',
            'email' => 'not an email'
        ));
        $module_auth = Mockery::mock('Module_Auth');
        $module_auth->shouldReceive('logged_in')->andReturn(NULL)->once();

        $context = new Context_User_Register($model_user, $module_auth);
        $result = $context->execute();

        $this->spec($result['status'])->should->be('failure');
        $this->spec($result['type'])->should->be('validation');
        $this->spec(array_key_exists('username', $result['errors']))->should->beTrue();
        $this->spec(array_key_exists('password', $result['errors']))->should->beTrue();
        $this->spec(array_key_exists('email', $result['errors']))->should->beTrue();
    }

    public function itReturnsSuccess()
    {
        $model_user = new Model_User(array(
            'username' => 'username',
            'password' => 'password',
            'email' => 'dion@thinkmoult.com'
        ));
        $module_auth = Mockery::mock('Module_Auth');
        $module_auth->shouldReceive('logged_in')->andReturn(NULL)->once();

        $context = new Context_User_Register($model_user, $module_auth);
        $result = $context->execute();

        $this->spec($result)->should->be(array(
            'status' => 'success'
        ));
    }

    public function itDefinesCastableRoles()
    {
        // Check object -> role casting relationship
        $cast = Mockery::mock('Cast_Guest[], Guest[]');

        $this->spec($cast)->should->beAnInstanceOf('Guest_Requirements');
        $this->spec($cast)->should->beAnInstanceOf('Model_User');

        // Check data object injection
        $model_user = new Model_User(array(
            'username' => 'username',
            'password' => 'password',
            'email' => 'email'
        ));
        $cast->__construct($model_user);

        $this->spec($cast->username)->should->be('username');
        $this->spec($cast->password)->should->be('password');
        $this->spec($cast->email)->should->be('email');

        // Check trait injection
        $guest = new Guest($model_user);
        $this->spec(method_exists($guest, 'validate_information'))->should->beTrue();
    }
}
