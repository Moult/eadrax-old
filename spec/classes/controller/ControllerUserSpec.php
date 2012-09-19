<?php

/**
 * TODO: Defining request, response, factory, context and controllers can be DRYer.
 * Split action specs into their own.
 */

class DescribeControllerUser extends \PHPSpec\Context
{
    public function before()
    {
        $request = Mockery::mock('request');
        $response = Mockery::mock('response');
        $this->_subject = new Controller_User($request, $response);
    }

    public function itUsesControllerCore()
    {
        $this->spec($this->_subject)->should->beAnInstanceOf('Controller_Core');
    }

    public function itShouldHaveActionRegister()
    {
        $this->spec(method_exists($this->_subject, 'action_register'))->should->beTrue();
    }

    public function itBypassesContextExecutionByDefaultForActionRegister()
    {
        $request = Mockery::mock('request', array(
            'method' => NULL
        ));
        $response = new Response;
        $this->_subject = new Controller_User($request, $response);
        $this->_subject->action_register();

        $this->spec($this->_subject->response->body())->shouldNot->beEmpty();
    }

    public function itRedirectsToTheDashboardUponContextSuccessForActionRegister()
    {
        $request = Mockery::mock('request', array(
            'method' => 'POST',
            'redirect' => NULL,
            'post' => array('foo' => 'bar')
        ));
        $response = new Response;

        $context = Mockery::mock('Context_User_Register[execute,data]');
        $context->shouldReceive('execute')->andReturn(array('status' => 'success'))->once();
        $context->shouldReceive('data')->andReturn(NULL)->once();

        $factory = Mockery::mock('Context_User_Register_Factory[fetch]');
        $factory->shouldReceive('fetch')->andReturn($context)->once();

        $controller = Mockery::mock('Controller_User[factory]');
        $controller->request = $request;
        $controller->response = $response;
        $controller->shouldReceive('factory')->andReturn($factory)->once();
        $controller->action_register();

        $this->spec($controller->response->body())->should->beEmpty();
    }

    public function itRedirectsToTheDashboardUponAuthorisationContextFailureForActionRegister()
    {
        $request = Mockery::mock('request', array(
            'method' => 'POST',
            'redirect' => NULL,
            'post' => array('foo' => 'bar')
        ));
        $response = new Response;

        $context = Mockery::mock('Context_User_Register[execute,data]');
        $context->shouldReceive('execute')->andReturn(array(
            'status' => 'failure',
            'type' => 'authorisation',
            'errors' => array('authorisation' => 'Foo')
        ))->once();
        $context->shouldReceive('data')->andReturn(NULL)->once();

        $factory = Mockery::mock('Context_User_Register_Factory[fetch]');
        $factory->shouldReceive('fetch')->andReturn($context)->once();

        $controller = Mockery::mock('Controller_User[factory]');
        $controller->shouldReceive('factory')->andReturn($factory)->once();
        $controller->request = $request;
        $controller->response = $response;
        $controller->action_register();

        $this->spec($controller->response->body())->should->beEmpty();
    }

    public function itReRendersTheViewUponNormalContextFailureForActionRegister()
    {
        $request = Mockery::mock('request', array(
            'method' => 'POST',
            'post' => array('foo' => 'bar')
        ));
        $response = new Response;

        $context = Mockery::mock('Context_User_Register[execute,data]');
        $context->shouldReceive('execute')->andReturn(array(
            'status' => 'failure',
            'errors' => array(
                'foo' => 'foo',
                'bar' => 'bar'
            )
        ))->once();
        $context->shouldReceive('data')->andReturn(NULL)->once();

        $factory = Mockery::mock('Context_User_Register_Factory[fetch]');
        $factory->shouldReceive('fetch')->andReturn($context)->once();

        $controller = Mockery::mock('Controller_User[factory]');
        $controller->shouldReceive('factory')->andReturn($factory)->once();
        $controller->request = $request;
        $controller->response = $response;

        $controller->action_register();

        $this->spec($controller->response->body())->shouldNot->beEmpty();
    }

    public function itHasToPassErrorsOntoTheViewForActionRegister()
    {
        $request = Mockery::mock('request[method]', array(
            'method' => 'POST',
            'post' => array('foo' => 'bar')
        ));
        $response = Mockery::mock('response[body]');

        $context = Mockery::mock('Context_User_Register[execute,data]');
        $context->shouldReceive('execute')->andReturn(array(
            'status' => 'failure',
            'errors' => array(
                'foo' => 'foo',
                'bar' => 'bar'
            )
        ))->once();
        $context->shouldReceive('data')->once();

        $factory = Mockery::mock('Context_User_Register_Factory[fetch]');
        $factory->shouldReceive('fetch')->andReturn($context)->once();

        $view = '';
        $response->shouldReceive('body')->andReturnUsing(function($body_view) use ( & $view) {
            $view = $body_view;
        })->once();

        $controller = Mockery::mock('Controller_User[factory]');
        $controller->request = $request;
        $controller->response = $response;
        $controller->shouldReceive('factory')->andReturn($factory)->once();

        $controller->action_register();

        $this->spec($view->errors)->should->beArray();
    }
}
