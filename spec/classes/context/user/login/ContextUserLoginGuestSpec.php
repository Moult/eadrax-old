<?php

class DescribeContextUserLoginGuest extends \PHPSpec\Context
{
    public function itDefinesAGuestRole()
    {
        $role = Mockery::mock('Context_User_Login_Guest[]');
        $this->spec($role)->should->beAnInstanceOf('Context_User_Login_Guest_Requirement');
        $this->spec($role)->should->beAnInstanceOf('Model_User');
    }

    public function itWillCastAGuestModelIntoAGuestRole()
    {
        $model_user = new Model_User(array(
            'username' => 'username',
            'password' => 'password',
        ));
        $cast = new Context_User_Login_Guest($model_user);

        $this->spec($cast->username)->should->be('username');
        $this->spec($cast->password)->should->be('password');
        $this->spec(method_exists($cast, 'authorise_login'))->should->beTrue();
    }
}
