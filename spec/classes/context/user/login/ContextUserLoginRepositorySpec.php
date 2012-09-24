<?php

class DescribeContextUserLoginRepository extends \PHPSpec\Context
{
    public function itChecksWhetherAnAccountExists()
    {
        $gateway_mysql_user = Mockery::mock('Gateway_Mysql_User');
        $gateway_mysql_user->shouldReceive('exists')->with(array(
            'username' => 'username',
            'password' => 'password'
        ))->andReturn(TRUE)->once();

        $repository = Mockery::mock('Context_User_Login_Repository[]');
        $repository->gateway_mysql_user = $gateway_mysql_user;
        $result = $repository->is_existing_account('username', 'password');
        $this->spec($result)->should->be(TRUE);
    }
}
