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

    public function itValidatesUserData()
    {
        $this->username = 'bad username';
        $this->password = '';
        $this->email = 'not an email';

        $this->spec(function() {
            $this->validate_information();
        })->should->throwException('Exception_Validation');

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
