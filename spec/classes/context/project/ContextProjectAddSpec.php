<?php

class DescribeContextProjectAdd extends \PHPSpec\Context
{
    public function itShouldBeAContext()
    {
        $this->spec(Mockery::mock('Context_Project_Add'))->should->beAnInstanceOf('Context_Core');
        $this->spec(method_exists(Mockery::mock('Context_Project_Add'), 'execute'))->should->beTrue();
    }

    public function itCatchesAuthorisationExceptionsDuringUseCaseExecution()
    {
        $user = Mockery::mock('Context_Project_Add_User[authorise_project_add]');
        $user->shouldReceive('authorise_project_add')->andThrow('Exception_Authorisation')->once();

        $context = Mockery::mock('Context_Project_Add[]');
        $context->user = $user;
        $result = $context->execute();

        $this->spec($result['status'])->should->be('failure');
        $this->spec($result['type'])->should->be('authorisation');
    }

    public function itCatchesValidationExceptionsDuringUseCaseExecution()
    {
        $user = Mockery::mock('Context_Project_Add_User[authorise_project_add]');
        $user->shouldReceive('authorise_project_add')->andThrow('Exception_Validation', array('foo' => 'bar'))->once();

        $context = Mockery::mock('Context_Project_Add[]');
        $context->user = $user;
        $result = $context->execute();

        $this->spec($result['status'])->should->be('failure');
        $this->spec($result['type'])->should->be('validation');
        $this->spec(array_key_exists('foo', $result['data']['errors']))->should->beTrue();
    }

    public function itReturnsSuccessWhenNoExceptionsThrown()
    {
        $user = Mockery::mock('Context_Project_Add_User[authorise_project_add]');
        $user->shouldReceive('authorise_project_add')->andReturn(TRUE)->once();

        $context = Mockery::mock('Context_Project_Add[]');
        $context->user = $user;
        $result = $context->execute();

        $this->spec($result)->should->be(array(
            'status' => 'success'
        ));
    }
}
