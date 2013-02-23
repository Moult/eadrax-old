<?php

namespace spec\Eadrax\Core\Usecase\Project\Prepare;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Project\Prepare\Proposal $proposal
     * @param Eadrax\Core\Usecase\Project\Prepare\Icon $icon
     */
    function let($proposal, $icon)
    {
        $this->beConstructedWith($proposal, $icon);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Prepare\Interactor');
    }

    function it_runs_the_interaction_chain($proposal, $icon)
    {
        $proposal->validate_information()->shouldBeCalled();
        $icon->exists()->shouldBeCalled()->willReturn(TRUE);
        $icon->validate_information()->shouldBeCalled();
        $icon->upload()->shouldBeCalled();
        $this->interact();
    }

    function it_only_uploads_if_an_icon_exists($icon)
    {
        $icon->exists()->shouldBeCalled()->willReturn(FALSE);
        $icon->upload()->shouldNotBeCalled();
        $this->interact();
    }

    function it_executes_the_usecase_succesfully()
    {
        $this->execute()->shouldReturn(array(
            'status' => 'success'
        ));
    }

    function it_catches_validation_exceptions_during_usecase_execution($proposal)
    {
        $proposal->validate_information()->willThrow('Eadrax\Core\Exception\Validation', array('foo' => 'bar'));
        $this->execute()->shouldReturn(Array(
            'status' => 'failure',
            'type' => 'validation',
            'data' => array(
                'errors' => array('foo' => 'bar')
            )
        ));
    }
}
