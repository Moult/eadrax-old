<?php

class DescribeContextUserRegisterRepository extends \PHPSpec\Context
{
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

        $repository = Mockery::mock('Context_User_Register_Repository[]');
        $repository->gateway_mysql_user = $gateway_mysql_user;
        $repository->register($model_user);
    }

    public function itChecksForUniqueUsernames()
    {
        $gateway_mysql_user = Mockery::mock('Gateway_Mysql_User');
        $gateway_mysql_user->shouldReceive('exists')->with(array(
            'username' => 'username',
        ))->andReturn(TRUE)->once();

        $repository = Mockery::mock('Context_User_Register_Repository[]');
        $repository->gateway_mysql_user = $gateway_mysql_user;
        $result = $repository->is_unique_username('username');
        $this->spec($result)->should->be(FALSE);
    }
}
