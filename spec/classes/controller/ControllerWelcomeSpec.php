<?php

class DescribeControllerWelcome extends \PHPSpec\Context
{
    public function before()
    {
        $request = Mockery::mock('request');
        $response = Mockery::mock('response');
        $this->_subject = new Controller_Welcome($request, $response);
    }

    public function itShouldBeAController()
    {
        $this->spec($this->_subject)->should->beAnInstanceOf('Controller');
    }

    public function itShouldRenderTheHomepage()
    {
        $this->_subject->response = new Response;
        $this->_subject->action_index();
        $this->spec($this->_subject->response->body())->shouldNot->beEmpty();
    }
}
