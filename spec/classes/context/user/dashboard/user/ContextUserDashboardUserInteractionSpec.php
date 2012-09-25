<?php

class DescribeContextUserDashboardUserInteraction extends \PHPSpec\Context
{
    use Context_User_Dashboard_User_Interaction;

    public function itReturnsAnAuthorisationErrorIfNotLoggedIn()
    {
        $module_auth = Mockery::mock('Module_Auth');
        $module_auth->shouldReceive('logged_in')->andReturn(FALSE)->once();

        $interaction = Mockery::mock('DescribeContextUserDashboardUserInteraction[]');
        $interaction->module_auth = $module_auth;

        $this->spec(function() use ($interaction) {
            $interaction->authorise_dashboard();
        })->should->throwException('Exception_Authorisation');
    }

    public function itReturnsWithUserDataIfLoggedIn()
    {
        $model_user = Mockery::mock('Model_User');
        $model_user->username = 'username';

        $module_auth = Mockery::mock('Module_Auth');
        $module_auth->shouldReceive('logged_in')->andReturn('username')->once();
        $module_auth->shouldReceive('get_user')->andReturn($model_user)->once();

        $interaction = Mockery::mock('DescribeContextUserDashboardUserInteraction[]');
        $interaction->module_auth = $module_auth;

        $result = $interaction->authorise_dashboard();
        $this->spec($result)->should->be(array(
            'username' => 'username'
        ));
    }
}
