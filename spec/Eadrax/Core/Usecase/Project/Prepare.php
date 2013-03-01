<?php

namespace spec\Eadrax\Core\Usecase\Project;

use PHPSpec2\ObjectBehavior;

class Prepare extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Tool\Validation $validation
     */
    function let($validation)
    {
        $data = array(
            'name' => 'Project name',
            'summary' => 'Project summary',
            'description' => 'Project description',
            'website' => 'http://projectwebsite.com/'
        );
        $tools = array(
            'validation' => $validation
        );
        $this->beConstructedWith($data, $tools);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Prepare');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Project\Prepare\Interactor');
    }
}
