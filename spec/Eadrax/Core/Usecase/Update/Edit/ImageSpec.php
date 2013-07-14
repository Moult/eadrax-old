<?php

namespace spec\Eadrax\Core\Usecase\Update\Edit;

use PhpSpec\ObjectBehavior;

class ImageSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Image $image
     * @param Eadrax\Core\Data\File $file
     * @param Eadrax\Core\Usecase\Update\Edit\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($image, $file, $repository, $authenticator)
    {
        $file->name = 'name';
        $file->tmp_name = 'tmp_name';
        $file->mimetype = 'mimetype';
        $file->filesize_in_bytes = 'filesize_in_bytes';
        $file->error_code = 'error_code';
        $image->id = 'update_id';
        $image->private = 'update_private';
        $image->file = $file;
        $image->thumbnail = 'tmp_name.thumb.png';
        $image->width = 'width';
        $image->height = 'height';
        $repository->get_author_id('update_id')->shouldBeCalled()->willReturn('project_author_id');
        $this->beConstructedWith($image, $repository, $authenticator);
        $this->load_prepared_proposal($image);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Edit\Image');
    }

    function it_should_be_a_proposal()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Edit\Proposal');
    }

    function it_should_be_an_image()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Image');
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

    function it_submits_the_image($repository)
    {
        $repository->purge_files('update_id')->shouldBeCalled();
        $repository->save_file('name', 'tmp_name', 'mimetype', 'filesize_in_bytes', 'error_code')->shouldBeCalled()->willReturn('file_path');
        $repository->save_generated_file('tmp_name.thumb.png')->shouldBeCalled()->willReturn('thumbnail_path');
        $repository->save_image('update_id', 'update_private', 'file_path', 'thumbnail_path', 'width', 'height')->shouldBeCalled();

        $this->submit();
    }
}
