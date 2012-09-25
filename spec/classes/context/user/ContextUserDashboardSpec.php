<?php

class DescribeContextUserDashboard extends \PHPSpec\Context
{
    public function itShouldBeAContext()
    {
        $this->spec(Mockery::mock('Context_User_Dashboard'))->should->beAnInstanceOf('Context_Core');
        $this->spec(method_exists(Mockery::mock('Context_User_Dashboard'), 'execute'))->should->beTrue();
    }

    public function itCatchesAuthorisationExceptionsDuringUseCaseExecution()
    {
        $user = Mockery::mock('Context_User_Dashboard_User[authorise_dashboard]');
        $user->shouldReceive('authorise_dashboard')->andThrow('Exception_Authorisation')->once();

        $context = Mockery::mock('Context_User_Dashboard[]');
        $context->user = $user;
        $result = $context->execute();

        $this->spec($result['status'])->should->be('failure');
        $this->spec($result['type'])->should->be('authorisation');
    }

    public function itReturnsSuccessWhenNoExceptionsThrown()
    {
        $user = Mockery::mock('Context_User_Dashboard_User[authorise_dashboard]');
        $user->shouldReceive('authorise_dashboard')->andReturn('data')->once();

        $context = Mockery::mock('Context_User_Dashboard[]');
        $context->user = $user;
        $result = $context->execute();

        $this->spec($result)->should->be(array(
            'status' => 'success',
            'data'   => 'data'
        ));
    }
}
