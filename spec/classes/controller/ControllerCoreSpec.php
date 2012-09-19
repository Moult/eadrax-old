<?php

class DescribeControllerCore extends \PHPSpec\Context
{
    public function before()
    {
        $request = Mockery::mock('request');
        $response = Mockery::mock('response');
        $this->_subject = Mockery::mock('Controller_Core[]');
        $this->_subject->request = $request;
        $this->_subject->response = $response;
    }

    public function itShouldBeAController()
    {
        $this->spec($this->_subject)->should->beAnInstanceOf('Controller');
    }

    public function itCanHandleRequestedFactories()
    {
        $subject = $this->_subject;
        $this->spec(function() use ($subject) {
            $subject->factory('Non_Existing_Factory');
        })->should->throwException('HTTP_Exception_404');
    }

    public function itCanAutoloadAFactory()
    {
        $request = Mockery::mock('request', array(
            'uri' => 'foo/bar'
        ));
        $response = new Response;
        $controller = Mockery::mock('Controller_Core[]');
        $controller->request = $request;
        $controller->response = $response;
        $this->spec(function() use ($controller) {
            $controller->factory();
        })->should->throwException('HTTP_Exception_404', 'Factory Context_Foo_Bar_Factory not found.');
    }
}
