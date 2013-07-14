<?php

namespace spec\Eadrax\Core\Usecase\Update\Edit;

use PhpSpec\ObjectBehavior;

class WebsiteSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Website $website
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Update\Edit\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($website, $repository, $authenticator)
    {
        $website->id = 'update_id';
        $website->private = 'update_private';
        $website->url = 'url';
        $website->thumbnail = 'screenshot_path';
        $this->beConstructedWith($website, $repository, $authenticator);
        $this->load_prepared_proposal($website);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Edit\Website');
    }

    function it_should_be_a_proposal()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Edit\Proposal');
    }

    function it_should_be_a_website()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Website');
    }

    function it_submits_the_website($repository)
    {
        $repository->purge_files('update_id')->shouldBeCalled();
        $repository->save_generated_file('screenshot_path')->shouldBeCalled()->willReturn('thumbnail_path');
        $repository->save_website('update_id', 'update_private', 'http://url', 'thumbnail_path')->shouldBeCalled()->willReturn('update_id');

        $this->submit();
    }
}
