<?php

namespace spec\Eadrax\Core\Usecase\Update\Edit;

use PhpSpec\ObjectBehavior;

class SoundSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Sound $sound
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Data\File $file
     * @param Eadrax\Core\Usecase\Update\Edit\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($sound, $file, $repository, $authenticator)
    {
        $file->name = 'name';
        $file->tmp_name = 'tmp_name';
        $file->mimetype = 'mimetype';
        $file->filesize_in_bytes = 'filesize_in_bytes';
        $file->error_code = 'error_code';
        $sound->id = 'update_id';
        $sound->private = 'update_private';
        $sound->file = $file;
        $sound->thumbnail = 'tmp_name.thumb.png';
        $sound->length = 'length';
        $sound->filesize = 'filesize';
        $this->beConstructedWith($sound, $repository, $authenticator);
        $this->load_prepared_proposal($sound);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Edit\Sound');
    }

    function it_should_be_a_proposal()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Edit\Proposal');
    }

    function it_should_be_a_sound()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Sound');
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

    function it_submits_the_sound($repository)
    {
        $repository->purge_files('update_id')->shouldBeCalled();
        $repository->save_file('name', 'tmp_name', 'mimetype', 'filesize_in_bytes', 'error_code')->shouldBeCalled()->willReturn('file_path');
        $repository->save_generated_file('tmp_name.thumb.png')->shouldBeCalled()->willReturn('thumbnail_path');
        $repository->save_sound('update_id', 'update_private', 'file_path', 'thumbnail_path', 'length', 'filesize')->shouldBeCalled()->willReturn('update_id');

        $this->submit();
    }
}
