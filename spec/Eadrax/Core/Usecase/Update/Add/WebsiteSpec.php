<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PhpSpec\ObjectBehavior;

class WebsiteSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Website $website
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Update\Add\Repository $repository
     */
    function let($website, $project, $repository)
    {
        $project->id = 'project_id';
        $website->project = $project;
        $website->private = 'update_private';
        $website->url = 'url';
        $website->thumbnail = 'screenshot_path';
        $this->beConstructedWith($repository);
        $this->load_prepared_proposal($website);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Website');
    }

    function it_should_be_a_proposal()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Proposal');
    }

    function it_should_be_a_website()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Website');
    }

    function it_submits_the_website($repository)
    {
        $repository->save_generated_file('screenshot_path')->shouldBeCalled()->willReturn('thumbnail_path');
        $repository->save_website('project_id', 'update_private', 'http://url', 'thumbnail_path')->shouldBeCalled()->willReturn('update_id');

        $this->submit();
        $this->id->shouldBe('update_id');
        $this->get_id()->shouldReturn('update_id');
    }

}
