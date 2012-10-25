<?php

class DescribeContextProjectAddProposalInteraction extends \PHPSpec\Context
{
    use Context_Project_Add_Proposal_Interaction;

    public function itSetsAnAuthor()
    {
        $model_user = Mockery::mock('Model_User');

        $interaction = Mockery::mock('DescribeContextProjectAddProposalInteraction[set_author,validate_information]');
        $interaction->shouldReceive('set_author')->with('Model_User')->once();
        $interaction->shouldReceive('validate_information')->once();
        $interaction->assign_author($model_user);
    }

    public function itValidatesIncomingData()
    {
        $interaction = Mockery::mock('DescribeContextProjectAddProposalInteraction[]');
        $model_user = Mockery::mock('Model_User');

        $interaction->name = ''; // Empty name
        $interaction->author = $model_user; // Already "validated" via assign_author
        $interaction->summary = ''; // Empty summary

        $this->spec(function() use ($interaction) {
            $interaction->validate_information();
        })->should->throwException('Exception_Validation', 'Multiple exceptions thrown: name, summary');
    }

    public function itSubmitsWithValidData()
    {
        $interaction = Mockery::mock('DescribeContextProjectAddProposalInteraction[submit]');
        $interaction->shouldReceive('submit')->once();
        $model_user = Mockery::mock('Model_User');

        $interaction->name = 'Project name';
        $interaction->author = $model_user;
        $interaction->summary = 'Project summary';

        $interaction->validate_information();
    }

    public function itSubmitsTheProposal()
    {
        $interaction = Mockery::mock('DescribeContextProjectAddProposalInteraction[]');
        $repository = Mockery::mock('Repository');
        $repository->shouldReceive('add_project')->with('DescribeContextProjectAddProposalInteraction')->once();

        $interaction->repository = $repository;
        $interaction->submit();
    }
}
