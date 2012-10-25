<?php

class DescribeContextProjectAddProposal extends \PHPSpec\Context
{
    public function itDefinesAProposalRole()
    {
        $role = Mockery::mock('Context_Project_Add_Proposal[]');
        $this->spec($role)->should->beAnInstanceOf('Context_Project_Add_Proposal_Requirement');
        $this->spec($role)->should->beAnInstanceOf('Model_Project');
    }

    public function itWillCastAProjectModelIntoAProposalRole()
    {
        $model_project = new Model_Project(array(
            'name' => 'name',
            'summary' => 'summary',
            'author' => new Model_User()
        ));
        $cast = new Context_Project_Add_Proposal($model_project);

        $this->spec($cast->name)->should->be('name');
        $this->spec($cast->summary)->should->be('summary');
        $this->spec(method_exists($cast, 'validate_information'))->should->beTrue();
    }
}
