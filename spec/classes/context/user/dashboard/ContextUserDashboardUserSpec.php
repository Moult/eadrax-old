<?php

class DescribeContextUserDashboardUser extends \PHPSpec\Context
{
    public function itDefinesAUserRole()
    {
        $role = Mockery::mock('Context_User_Dashboard_User[]');
        $this->spec($role)->should->beAnInstanceOf('Context_User_Dashboard_User_Requirement');
        $this->spec($role)->should->beAnInstanceOf('Model_User');
    }

    public function itWillCastAUserModelIntoAUserRole()
    {
        $model_user = new Model_User(array(
            'username' => 'username',
            'password' => 'password',
        ));
        $cast = new Context_User_Dashboard_User($model_user);

        $this->spec($cast->username)->should->be('username');
        $this->spec($cast->password)->should->be('password');
        $this->spec(method_exists($cast, 'authorise_dashboard'))->should->beTrue();
    }
}
