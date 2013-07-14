<?php

namespace spec\Eadrax\Core\Usecase\Update\Edit;

use PhpSpec\ObjectBehavior;

class VideoSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Video $video
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Update\Edit\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($video, $repository, $authenticator)
    {
        $video->id = 'update_id';
        $video->private = 'update_private';
        $video->file = 'tmp_name.webm';
        $video->thumbnail = 'tmp_name.webm.thumb.png';
        $video->length = 'length';
        $video->filesize = 'filesize';
        $video->width = 'width';
        $video->height = 'height';
        $this->beConstructedWith($video, $repository, $authenticator);
        $this->load_prepared_proposal($video);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Edit\Video');
    }

    function it_should_be_a_proposal()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Edit\Proposal');
    }

    function it_should_be_a_video()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Video');
    }

    /**
     * @param Eadrax\Core\Data\User $logged_in_user
     */
    function it_authorises_ownership($logged_in_user, $authenticator)
    {
        $logged_in_user->id = 'impostor_id';
        $authenticator->get_user()->shouldBeCalled()->willReturn($logged_in_user);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise_ownership();
    }

    function it_submits_the_video($repository)
    {
        $repository->purge_files('update_id')->shouldBeCalled();
        $repository->save_generated_file('tmp_name.webm')->shouldBeCalled()->willReturn('file_path');
        $repository->save_generated_file('tmp_name.webm.thumb.png')->shouldBeCalled()->willReturn('thumbnail_path');
        $repository->save_video('update_id', 'update_private', 'file_path', 'thumbnail_path', 'length', 'filesize', 'width', 'height')->shouldBeCalled()->willReturn('update_id');

        $this->submit();
    }
}
