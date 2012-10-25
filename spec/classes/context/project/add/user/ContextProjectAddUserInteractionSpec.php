<?php

class DescribeContextProjectAddUserInteraction extends \PHPSpec\Context
{
    use Context_Project_Add_User_Interaction;

    public function itReturnsAnAuthorisationErrorIfNotLoggedIn()
    {
        $module_auth = Mockery::mock('Module_Auth');
        $module_auth->shouldReceive('logged_in')->andReturn(FALSE)->once();

        $interaction = Mockery::mock('DescribeContextProjectAddUserInteraction[]');
        $interaction->module_auth = $module_auth;

        $this->spec(function() use ($interaction) {
            $interaction->authorise_project_add();
        })->should->throwException('Exception_Authorisation');
    }

    public function itAsksProposalToSetItselfAsAuthorIfLoggedIn()
    {
        $module_auth = Mockery::mock('Module_Auth');
        $module_auth->shouldReceive('logged_in')->andReturn('username')->once();

        $proposal = Mockery::mock('Proposal');
        $proposal->shouldReceive('set_author')->with('Foo')->andReturn(TRUE)->once();

        $interaction = Mockery::mock('DescribeContextProjectAddUserInteraction[]');
        $interaction->module_auth = $module_auth;
        $interaction->proposal = $proposal;
        $interaction->id = 'Foo';

        $interaction->authorise_project_add();
    }
}
