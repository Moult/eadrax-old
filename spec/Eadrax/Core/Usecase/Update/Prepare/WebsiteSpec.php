<?php

namespace spec\Eadrax\Core\Usecase\Update\Prepare;

use PhpSpec\ObjectBehavior;

class WebsiteSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Website $website
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Tool\Browser $browser
     * @param Eadrax\Core\Tool\Photoshopper $photoshopper
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($website, $project, $browser, $photoshopper, $validator)
    {
        $project->id = 'project_id';
        $website->project = $project;
        $website->private = 'update_private';
        $website->url = 'url';
        $this->beConstructedWith($website, $browser, $photoshopper, $validator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Prepare\Website');
    }

    function it_should_be_a_proposal()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Prepare\Proposal');
    }

    function it_should_be_a_website()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Website');
    }

    function it_validates_websites($validator)
    {
        $validator->setup(array('url' => 'http://url'))->shouldBeCalled();
        $validator->rule('url', 'not_empty')->shouldBeCalled();
        $validator->rule('url', 'url')->shouldBeCalled();
        $validator->rule('url', 'url_domain')->shouldBeCalled();
        $validator->check()->shouldBeCalled();
        $validator->errors()->shouldBeCalled()->willReturn(array('url'));
        $this->shouldThrow('Eadrax\Core\Exception\Validation')
            ->duringValidate();
    }

    function it_generates_a_thumbnail($browser, $photoshopper)
    {
        $browser->screenshot('http://url')->shouldBeCalled()->willReturn('screenshot_path');
        $photoshopper->setup('screenshot_path')->shouldBeCalled();
        $photoshopper->resize_to_height(100)->shouldBeCalled();
        $this->generate_thumbnail();
        $this->thumbnail->shouldBe('screenshot_path');
    }
}
