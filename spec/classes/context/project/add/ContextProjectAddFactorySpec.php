<?php

class DescribeContextProjectAddFactory extends \PHPSpec\Context
{
    public function itIsAFactory()
    {
        $factory = Mockery::mock('Context_Project_Add_Factory');
        $this->spec($factory)->should->beAnInstanceOf('Context_Factory');
    }

    public function itShouldCreateAContext()
    {
        $model_user = Mockery::mock('Model_User');

        $model_project = Mockery::mock('Model_Project');
        $model_project->name = 'Example project';
        $model_project->author = $model_user;

        $factory = Mockery::mock('Context_Project_Add_Factory[model_user,model_project,module_auth]');

        $factory->shouldReceive('model_user')->andReturn($model_user)->once();
        $factory->shouldReceive('model_project')->andReturn($model_project)->once();
        $factory->shouldReceive('module_auth')->andReturn(Mockery::mock('Auth'))->once();

        $context = $factory->fetch();
        $this->spec($context)->should->beAnInstanceOf('Context_Project_Add');
    }

    public function itShouldAutoAllocateDataToModels()
    {
        $factory = new Context_Project_Add_Factory(array(
            'id' => 'id',
            'name' => 'name',
            'summary' => 'summary'
        ));

        $model_user = $factory->model_user();
        $this->spec($model_user->id)->should->be('id');

        $model_project = $factory->model_project();
        $this->spec($model_project->name)->should->be('name');
        $this->spec($model_project->summary)->should->be('summary');
    }
}
