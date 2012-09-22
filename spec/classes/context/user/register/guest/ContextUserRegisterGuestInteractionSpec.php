<?php

class DescribeContextUserRegisterGuestInteraction extends \PHPSpec\Context
{
    use Context_User_Register_Guest_Interaction;

    public function itReturnsAnAuthorisationErrorIfLoggedIn()
    {
        $module_auth = Mockery::mock('Module_Auth');
        $module_auth->shouldReceive('logged_in')->andReturn('Username')->once();

        $interaction = Mockery::mock('DescribeContextUserRegisterGuestInteraction[]');
        $interaction->module_auth = $module_auth;

        $this->spec(function() use ($interaction) {
            $interaction->authorise_registration();
        })->should->throwException('Exception_Authorisation');
    }

    public function itContinuesToValidateInformationIfNotLoggedIn()
    {
        $module_auth = Mockery::mock('Module_Auth');
        $module_auth->shouldReceive('logged_in')->andReturn(FALSE)->once();

        $interaction = Mockery::mock('DescribeContextUserRegisterGuestInteraction[validate_information]');
        $interaction->shouldReceive('validate_information')->once();
        $interaction->module_auth = $module_auth;

        $interaction->authorise_registration();
    }

    public function itValidatesUsernames()
    {
        $repository = Mockery::mock('Repository');
        $repository->shouldReceive('is_unique_username')->andReturn(FALSE)->once();

        $interaction = Mockery::mock('DescribeContextUserRegisterGuestInteraction[]');
        $interaction->repository = $repository;
        $interaction->username = ''; // No username
        $interaction->password = 'goodpassword';
        $interaction->email = 'good@email.com';

        $this->spec(function() use ($interaction) {
            $interaction->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: username');

        $interaction->username = 'asdf *!@#$%'; // Funny chars

        $this->spec(function() use ($interaction) {
            $interaction->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: username');

        $interaction->username = 'x'; // Too short

        $this->spec(function() use ($interaction) {
            $interaction->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: username');

        $interaction->username = 'once_upon_a_time_there_was_a_long_username'; // Too long

        $this->spec(function() use ($interaction) {
            $interaction->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: username');

        $interaction->username = 'existing_user'; // Already existing

        $this->spec(function() use ($interaction) {
            $interaction->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: username');
    }

    public function itCanCheckForUniqueUsernames()
    {
        $interaction = Mockery::mock('DescribeContextUserRegisterGuestInteraction[]');

        $repository = Mockery::mock('Repository');
        $repository->shouldReceive('is_unique_username')->with('username')->once();

        $interaction->repository = $repository;

        $interaction->is_unique_username('username');
    }

    public function itValidatesPasswords()
    {
        $repository = Mockery::mock('Repository');
        $repository->shouldReceive('is_unique_username')->andReturn(TRUE)->twice();

        $interaction = Mockery::mock('DescribeContextUserRegisterGuestInteraction[]');
        $interaction->repository = $repository;

        $interaction->username = 'good_username';
        $interaction->password = ''; // No password
        $interaction->email = 'good@email.com';

        $this->spec(function() use ($interaction) {
            $interaction->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: password');

        $interaction->password = '12345'; // < 6 chars

        $this->spec(function() use ($interaction) {
            $interaction->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: password');
    }

    public function itValidatesEmails()
    {
        $repository = Mockery::mock('Repository');
        $repository->shouldReceive('is_unique_username')->andReturn(TRUE)->twice();

        $interaction = Mockery::mock('DescribeContextUserRegisterGuestInteraction[]');
        $interaction->repository = $repository;

        $interaction->username = 'good_username';
        $interaction->password = 'goodpassword';
        $interaction->email = ''; // No email

        $this->spec(function() use ($interaction) {
            $interaction->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: email');

        $interaction->email = 'fake email'; // Not an email address

        $this->spec(function() use ($interaction) {
            $interaction->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: email');
    }

    public function itRegistersValidData()
    {
        $repository = Mockery::mock('Repository');
        $repository->shouldReceive('is_unique_username')->andReturn(TRUE)->once();

        $interaction = Mockery::mock('DescribeContextUserRegisterGuestInteraction[register]');
        $interaction->repository = $repository;

        $interaction->username = 'username';
        $interaction->password = 'password';
        $interaction->email = 'dion@thinkmoult.com';

        $interaction->shouldReceive('register')->once();

        $this->spec(function() use ($interaction) {
            $interaction->validate_information();
        })->shouldNot->throwException('Exception_Validation');
    }

    public function itCanRegister()
    {
        $interaction = Mockery::mock('DescribeContextUserRegisterGuestInteraction[login]');
        $interaction->shouldReceive('login')->once();

        $repository = Mockery::mock('Repository');
        $repository->shouldReceive('register')->with($interaction)->once();

        $interaction->repository = $repository;

        $interaction->register();
    }

    public function itLogsTheGuestInSuccessfully()
    {
        $module_auth = Mockery::mock('Module_Auth');
        $module_auth->shouldReceive('login')->with('username', 'password')->andReturn(TRUE)->once();

        $interaction = Mockery::mock('DescribeContextUserRegisterGuestInteraction[]');
        $interaction->username = 'username';
        $interaction->password = 'password';
        $interaction->module_auth = $module_auth;

        $interaction->login();
    }
}
