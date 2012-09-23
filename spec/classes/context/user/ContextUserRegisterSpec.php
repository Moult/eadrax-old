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
        $guest = Mockery::mock('Guest[authorise_registration]');
        $guest->shouldReceive('authorise_registration')->andThrow('Exception_Authorisation')->once();

        $context = Mockery::mock('Context_User_Register[]');
        $context->guest = $guest;
        $result = $context->execute();

        $this->spec($result['status'])->should->be('failure');
        $this->spec($result['type'])->should->be('authorisation');
    }

    public function itCatchesValidationExceptionsDuringUseCaseExecution()
    {
        $guest = Mockery::mock('Guest[authorise_registration]');
        $guest->shouldReceive('authorise_registration')->andThrow('Exception_Validation', array('foo' => 'bar'))->once();

        $context = Mockery::mock('Context_User_Register[]');
        $context->guest = $guest;
        $result = $context->execute();

        $this->spec($result['status'])->should->be('failure');
        $this->spec($result['type'])->should->be('validation');
        $this->spec(array_key_exists('foo', $result['data']['errors']))->should->beTrue();
    }

    public function itReturnsSuccessWhenNoExceptionsThrown()
    {
        $guest = Mockery::mock('Guest[authorise_registration]');
        $guest->shouldReceive('authorise_registration')->andReturn(TRUE)->once();

        $context = Mockery::mock('Context_User_Register[]');
        $context->guest = $guest;
        $result = $context->execute();

        $this->spec($result)->should->be(array(
            'status' => 'success'
        ));
    }

    public function itDefinesAGuestCast()
    {
        $cast = Mockery::mock('Cast_Guest[]');
        $this->spec($cast)->should->beAnInstanceOf('Guest_Requirements');
        $this->spec($cast)->should->beAnInstanceOf('Model_User');
    }

    public function itDefinesAGuestRole()
    {
        $cast = Mockery::mock('Guest[]');
        $this->spec($cast)->should->beAnInstanceOf('Cast_Guest');
    }

    public function itWillCastAGuestModelIntoAGuestRole()
    {
        $model_user = new Model_User(array(
            'username' => 'username',
            'password' => 'password',
            'email' => 'email'
        ));
        $cast = new Guest($model_user);

        $this->spec($cast->username)->should->be('username');
        $this->spec($cast->password)->should->be('password');
        $this->spec($cast->email)->should->be('email');
        $this->spec(method_exists($cast, 'authorise_registration'))->should->beTrue();
    }
}
