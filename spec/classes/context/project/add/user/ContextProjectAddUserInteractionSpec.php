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

    public function itSetsItselfAsTheCurrentlyLoggedInUserIfLoggedIn()
    {
        $module_auth = Mockery::mock('Module_Auth');
        $module_auth->shouldReceive('logged_in')->andReturn('username')->once();

        $interaction = Mockery::mock('DescribeContextProjectAddUserInteraction[load_authentication_details]');
        $interaction->shouldReceive('load_authentication_details')->once();
        $interaction->module_auth = $module_auth;

        $interaction->authorise_project_add();
    }

    public function itLoadsAuthenticationDetailsThenAsksProposalToSetItselfAsAuthor()
    {
        $model_user = Mockery::mock('Model_User');
        $model_user->id = 'id';
        $model_user->username = 'username';

        $proposal = Mockery::mock('Proposal');
        $proposal->shouldReceive('assign_author')->with('DescribeContextProjectAddUserInteraction')->andReturn(TRUE)->once();

        $module_auth = Mockery::mock('Module_Auth');
        $module_auth->shouldReceive('get_user')->andReturn($model_user)->once();

        $interaction = Mockery::mock('DescribeContextProjectAddUserInteraction[set_username,set_id]');
        $interaction->shouldReceive('set_username')->with('username')->once();
        $interaction->shouldReceive('set_id')->with('id')->once();

        $interaction->module_auth = $module_auth;
        $interaction->proposal = $proposal;

        $interaction->load_authentication_details();
    }
}
