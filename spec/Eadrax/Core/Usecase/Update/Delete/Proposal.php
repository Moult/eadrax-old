<?php

namespace spec\Eadrax\Core\Usecase\Update\Delete;

use PHPSpec2\ObjectBehavior;

class Proposal extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Update $update
     * @param Eadrax\Core\Data\Update $update_details
     * @param Eadrax\Core\Usecase\Update\Delete\Repository $repository
     * @param Eadrax\Core\Tool\Filesystem $filesystem
     */
    function let($update, $update_details, $repository, $filesystem)
    {
        $update->id = 'id';
        $update_details->type = 'text';
        $update_details->content = 'foo';
        $repository->get_update_type_and_content('id')->willReturn($update_details);
        $this->beConstructedWith($update, $repository, $filesystem);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Delete\Proposal');
        $this->id->shouldBe('id');
        $this->type->shouldBe('text');
        $this->content->shouldBe('foo');
    }

    function it_should_be_an_update()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Update');
    }

    function it_can_delete_thumbnails($update_details, $repository, $filesystem)
    {
        $update_details->type = 'file/image';
        $update_details->content = 'foo.png';
        $repository->get_update_type_and_content('id')->willReturn($update_details);
        $filesystem->delete_thumbnail('foo.png')->shouldBeCalled();
        $this->delete_thumbnail();
    }

    function it_can_delete_uploads($update_details, $repository, $filesystem)
    {
        $update_details->type = 'file/image';
        $update_details->content = 'foo.png';
        $repository->get_update_type_and_content('id')->willReturn($update_details);
        $filesystem->delete_upload('foo.png')->shouldBeCalled();
        $this->delete_upload();
    }

    function it_can_delete_the_update($repository)
    {
        $repository->delete_update($this)->shouldBeCalled();
        $this->delete();
    }
}
