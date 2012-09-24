<?php

class DescribeContextUserLoginGuestInteraction extends \PHPSpec\Context
{
    use Context_User_Login_Guest_Interaction;

    public function itReturnsAnAuthorisationErrorIfLoggedIn()
    {
        $module_auth = Mockery::mock('Module_Auth');
        $module_auth->shouldReceive('logged_in')->andReturn('Username')->once();

        $interaction = Mockery::mock('DescribeContextUserLoginGuestInteraction[]');
        $interaction->module_auth = $module_auth;

        $this->spec(function() use ($interaction) {
            $interaction->authorise_login();
        })->should->throwException('Exception_Authorisation');
    }

    public function itContinuesToValidateInformationIfNotLoggedIn()
    {
        $module_auth = Mockery::mock('Module_Auth');
        $module_auth->shouldReceive('logged_in')->andReturn(FALSE)->once();

        $interaction = Mockery::mock('DescribeContextUserLoginGuestInteraction[validate_information]');
        $interaction->shouldReceive('validate_information')->once();
        $interaction->module_auth = $module_auth;

        $interaction->authorise_login();
    }

    public function itValidatesAccountInformation()
    {
        $repository = Mockery::mock('Repository');
        $repository->shouldReceive('is_existing_account')->andReturn(FALSE)->once();

        $interaction = Mockery::mock('DescribeContextUserLoginGuestInteraction[]');
        $interaction->repository = $repository;
        $interaction->username = 'badusername';
        $interaction->password = 'badpassword';

        $this->spec(function() use ($interaction) {
            $interaction->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: username');

        $interaction->username = '';
        $interaction->password = '';

        $this->spec(function() use ($interaction) {
            $interaction->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: username');
    }

    public function itWillLoginWithValidData()
    {
        $repository = Mockery::mock('Repository');
        $repository->shouldReceive('is_existing_account')->andReturn(TRUE)->once();

        $interaction = Mockery::mock('DescribeContextUserLoginGuestInteraction[login]');
        $interaction->repository = $repository;

        $interaction->username = 'username';
        $interaction->password = 'password';

        $interaction->shouldReceive('login')->once();

        $this->spec(function() use ($interaction) {
            $interaction->validate_information();
        })->shouldNot->throwException('Exception_Validation');
    }

    public function itLogsTheGuestInSuccessfully()
    {
        $module_auth = Mockery::mock('Module_Auth');
        $module_auth->shouldReceive('login')->with('username', 'password')->andReturn(TRUE)->once();

        $interaction = Mockery::mock('DescribeContextUserLoginGuestInteraction[]');
        $interaction->username = 'username';
        $interaction->password = 'password';
        $interaction->module_auth = $module_auth;

        $interaction->login();
    }
}
