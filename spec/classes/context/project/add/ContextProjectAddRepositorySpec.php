<?php

class DescribeContextProjectAddRepository extends \PHPSpec\Context
{
    public function itAddsAProject()
    {
        $gateway_mysql_project = Mockery::mock('Gateway_Mysql_Project');
        $gateway_mysql_project->shouldReceive('insert')->with(array(
            'name' => 'name',
            'summary' => 'summary',
            'uid' => 'foo'
        ))->once();

        $model_user = Mockery::mock('Model_User');
        $model_user->id = 'foo';

        $model_project = Mockery::mock('Model_Project');
        $model_project->name = 'name';
        $model_project->summary = 'summary';
        $model_project->author = $model_user;

        $repository = Mockery::mock('Context_Project_Add_Repository[]');
        $repository->gateway_mysql_project = $gateway_mysql_project;
        $repository->add_project($model_project);
    }
}
