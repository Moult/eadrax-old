<?php

class DescribeContextUserRegisterGuestInteraction extends \PHPSpec\Context
{
    use Context_Interaction;
    use Context_User_Register_Guest_Interaction;

    public function itReturnsAnAuthorisationErrorIfLoggedIn()
    {
        $module_auth = Mockery::mock('Module_Auth');
        $module_auth->shouldReceive('logged_in')->andReturn('Username')->once();
        $model_user = Mockery::mock('Model_User');
        $this->link(array(
            'module_auth' => $module_auth
        ));

        $this->spec(function() {
            $this->authorise_registration();
        })->should->throwException('Exception_Authorisation');
    }

    public function itValidatesUsernames()
    {
        $this->username = ''; // No username
        $this->password = 'goodpassword';
        $this->email = 'good@email.com';

        $this->spec(function() {
            $this->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: username');

        $this->username = 'asdf *!@#$%'; // Funny chars

        $this->spec(function() {
            $this->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: username');

        $this->username = 'x'; // Too short

        $this->spec(function() {
            $this->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: username');

        $this->username = 'once_upon_a_time_there_was_a_long_username'; // Too long

        $this->spec(function() {
            $this->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: username');

        $this->pending('Test for existing usernames');
    }

    public function itValidatesPasswords()
    {
        $this->username = 'good_username';
        $this->password = ''; // No password
        $this->email = 'good@email.com';

        $this->spec(function() {
            $this->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: password');

        $this->password = '12345'; // < 6 chars

        $this->spec(function() {
            $this->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: password');
    }

    public function itValidatesEmails()
    {
        $this->username = 'good_username';
        $this->password = 'goodpassword';
        $this->email = ''; // No email

        $this->spec(function() {
            $this->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: email');

        $this->email = 'fake email'; // Not an email address

        $this->spec(function() {
            $this->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: email');
    }

    public function itRegistersValidData()
    {
        $guest = Mockery::mock('DescribeContextUserRegisterGuestInteraction[register]');
        $guest->username = 'username';
        $guest->password = 'password';
        $guest->email = 'dion@thinkmoult.com';
        $guest->shouldReceive('register')->once();

        $this->spec(function() use ($guest) {
            $guest->validate_information();
        })->shouldNot->throwException('Exception_Validation');
    }

    public function itCanRegister()
    {
        $repository = Mockery::mock('Repository');

        $this->username = 'username';
        $this->password = 'password';
        $this->email = 'dion@thinkmoult.com';

        $this->link(array(
            'repository' => $repository
        ));

        $repository->shouldReceive('register')->with($this)->once();

        $this->register();
    }
}
