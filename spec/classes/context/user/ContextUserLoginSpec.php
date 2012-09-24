<?php

class DescribeContextUserLogin extends \PHPSpec\Context
{
    public function itShouldBeAContext()
    {
        $this->spec(Mockery::mock('Context_User_Login'))->should->beAnInstanceOf('Context_Core');
        $this->spec(method_exists(Mockery::mock('Context_User_Login'), 'execute'))->should->beTrue();
    }

    public function itCatchesAuthorisationExceptionsDuringUseCaseExecution()
    {
        $guest = Mockery::mock('Context_User_Login_Guest[authorise_login]');
        $guest->shouldReceive('authorise_login')->andThrow('Exception_Authorisation')->once();

        $context = Mockery::mock('Context_User_Login[]');
        $context->guest = $guest;
        $result = $context->execute();

        $this->spec($result['status'])->should->be('failure');
        $this->spec($result['type'])->should->be('authorisation');
    }

    public function itCatchesValidationExceptionsDuringUseCaseExecution()
    {
        $guest = Mockery::mock('Context_User_Login_Guest[authorise_login]');
        $guest->shouldReceive('authorise_login')->andThrow('Exception_Validation', array('foo' => 'bar'))->once();

        $context = Mockery::mock('Context_User_Login[]');
        $context->guest = $guest;
        $result = $context->execute();

        $this->spec($result['status'])->should->be('failure');
        $this->spec($result['type'])->should->be('validation');
        $this->spec(array_key_exists('foo', $result['data']['errors']))->should->beTrue();
    }

    public function itReturnsSuccessWhenNoExceptionsThrown()
    {
        $guest = Mockery::mock('Context_User_Login_Guest[authorise_login]');
        $guest->shouldReceive('authorise_login')->andReturn(TRUE)->once();

        $context = Mockery::mock('Context_User_Login[]');
        $context->guest = $guest;
        $result = $context->execute();

        $this->spec($result)->should->be(array(
            'status' => 'success'
        ));
    }
}
