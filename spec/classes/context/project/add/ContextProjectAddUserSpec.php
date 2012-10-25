<?php

class DescribeContextProjectAddUser extends \PHPSpec\Context
{
    public function itDefinesAUserRole()
    {
        $role = Mockery::mock('Context_Project_Add_User[]');
        $this->spec($role)->should->beAnInstanceOf('Context_Project_Add_User_Requirement');
        $this->spec($role)->should->beAnInstanceOf('Model_User');
    }

    public function itWillCastAUserModelIntoAUserRole()
    {
        $model_user = new Model_User(array(
            'username' => 'username',
            'password' => 'password',
            'email' => 'email'
        ));
        $cast = new Context_Project_Add_User($model_user);

        $this->spec($cast->username)->should->be('username');
        $this->spec($cast->password)->should->be('password');
        $this->spec($cast->email)->should->be('email');
        $this->spec(method_exists($cast, 'authorise_project_add'))->should->beTrue();
    }
}
