<?php

class DescribeContextUserRegisterGuest extends \PHPSpec\Context
{
    public function itDefinesAGuestRole()
    {
        $role = Mockery::mock('Context_User_Register_Guest[]');
        $this->spec($role)->should->beAnInstanceOf('Context_User_Register_Guest_Requirement');
        $this->spec($role)->should->beAnInstanceOf('Model_User');
    }

    public function itWillCastAUserModelIntoAGuestRole()
    {
        $model_user = new Model_User(array(
            'username' => 'username',
            'password' => 'password',
            'email' => 'email'
        ));
        $cast = new Context_User_Register_Guest($model_user);

        $this->spec($cast->username)->should->be('username');
        $this->spec($cast->password)->should->be('password');
        $this->spec($cast->email)->should->be('email');
        $this->spec(method_exists($cast, 'authorise_registration'))->should->beTrue();
    }
}
