<?php

class DescribeContextUserRegisterRepository extends \PHPSpec\Context
{
    public function before()
    {
        $this->_subject = new Context_User_Register_Repository;
    }

    public function itRegistersAUser()
    {
        $gateway_mysql_user = Mockery::mock('Gateway_Mysql_User');
        $gateway_mysql_user->shouldReceive('insert')->with(array(
            'username' => 'username',
            'password' => 'password',
            'email' => 'dion@thinkmoult.com'
        ))->once();

        $model_user = Mockery::mock('Model_User');
        $model_user->username = 'username';
        $model_user->password = 'password';
        $model_user->email = 'dion@thinkmoult.com';

        $this->_subject->gateway_mysql_user = $gateway_mysql_user;
        $this->_subject->register($model_user);
    }
}
