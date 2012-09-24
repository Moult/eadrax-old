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
        $guest = Mockery::mock('Context_User_Register_Guest[authorise_registration]');
        $guest->shouldReceive('authorise_registration')->andThrow('Exception_Authorisation')->once();

        $context = Mockery::mock('Context_User_Register[]');
        $context->guest = $guest;
        $result = $context->execute();

        $this->spec($result['status'])->should->be('failure');
        $this->spec($result['type'])->should->be('authorisation');
    }

    public function itCatchesValidationExceptionsDuringUseCaseExecution()
    {
        $guest = Mockery::mock('Context_User_Register_Guest[authorise_registration]');
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
        $guest = Mockery::mock('Context_User_Register_Guest[authorise_registration]');
        $guest->shouldReceive('authorise_registration')->andReturn(TRUE)->once();

        $context = Mockery::mock('Context_User_Register[]');
        $context->guest = $guest;
        $result = $context->execute();

        $this->spec($result)->should->be(array(
            'status' => 'success'
        ));
    }
}
