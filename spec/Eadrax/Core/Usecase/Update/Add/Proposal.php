<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PHPSpec2\ObjectBehavior;

class Proposal extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Update $update
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Tool\Validation $validation
     */
    public function let($update, $project, $validation)
    {
        $update->type = 'type';
        $update->content = 'content';
        $update->extra = 'extra';
        $update->private = 'private';
        $update->project = $project;
        $this->beConstructedWith($update, $validation);
    }

    function it_should_be_initializable($project)
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Proposal');
        $this->type->shouldBe('type');
        $this->content->shouldBe('content');
        $this->extra->shouldBe('extra');
        $this->private->shouldBe('private');
        $this->project->shouldBe($project);
    }

    function it_should_be_an_update()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Update');
    }

    function it_validates_text_updates($update, $validation)
    {
        $update->type = 'text';
        $update->content = 'Foobar';
        $validation->setup(array('content' => 'Foobar'))->shouldBeCalled();
        $validation->rule('content', 'not_empty')->shouldBeCalled();
        $validation->check()->shouldBeCalled()->willReturn(FALSE);
        $validation->errors()->shouldBeCalled()->willReturn(array('content'));
        $this->shouldThrow('Eadrax\Core\Exception\Validation')
            ->duringValidate();
        $this->extra->shouldBe(NULL);
    }

    function it_validates_paste_updates($update, $validation)
    {
        $update->type = 'paste';
        $update->extra = 'bash';
        $update->content = '#! /bin/bash';
        $validation->setup(array(
            'content' => '#! /bin/bash',
            'syntax' => 'bash'
        ))->shouldBeCalled();
        $validation->rule('content', 'not_empty')->shouldBeCalled();
        $validation->rule('syntax', 'not_empty')->shouldBeCalled();
        $validation->callback('syntax', array($this, 'validate_paste_syntax'), array('syntax'))->shouldBeCalled();
        $validation->check()->shouldBeCalled()->willReturn(FALSE);
        $validation->errors()->shouldBeCalled()->willReturn(array('content'));
        $this->shouldThrow('Eadrax\Core\Exception\Validation')
            ->duringValidate();
    }

    function it_validates_paste_syntax()
    {
        $this->validate_paste_syntax('bash')->shouldReturn(TRUE);
        $this->validate_paste_syntax('english')->shouldReturn(FALSE);
    }

    function it_validates_websites($update, $validation)
    {
        $update->type = 'website';
        $update->content = 'foobar.com';
        $validation->setup(array('content' => 'http://foobar.com'))->shouldBeCalled();
        $validation->rule('content', 'not_empty')->shouldBeCalled();
        $validation->rule('content', 'url')->shouldBeCalled();
        $validation->rule('content', 'url_domain')->shouldBeCalled();
        $validation->check()->shouldBeCalled();
        $validation->errors()->shouldBeCalled()->willReturn(array('content'));
        $this->shouldThrow('Eadrax\Core\Exception\Validation')
            ->duringValidate();
    }

    /**
     * @param Eadrax\Core\Data\File $file
     */
    function it_validates_files($file, $update, $validation)
    {
        $supported_filetypes = array('gif', 'jpg', 'jpeg', 'png', 'svg', 'tiff', 'bmp', 'exr', 'pdf', 'zip', 'rar', 'tar', 'gz', 'bz', '7z', 'ogg', 'ogv', 'wmv', 'mp3', 'wav', 'avi', 'mpg', 'mpeg', 'mov', 'swf', 'flv', 'blend', 'xcf', 'doc', 'ppt', 'xls', 'odt', 'ods', 'odp', 'odg', 'psd', 'fla', 'ai', 'indd', 'aep', 'txt', 'cab', 'csv', 'exe', 'diff', 'patch', 'rtf', 'torrent', 'mp4');

        $update->type = 'file';
        $update->content = $file;
        $validation->setup(array('content' => $file))->shouldBeCalled();
        $validation->rule('content', 'upload_valid')->shouldBeCalled();
        $validation->rule('content', 'upload_type', $supported_filetypes)->shouldBeCalled();
        $validation->rule('content', 'upload_size', '100M')->shouldBeCalled();
        $validation->check()->shouldBeCalled();
        $validation->errors()->shouldBeCalled()->willReturn(array('content'));
        $this->shouldThrow('Eadrax\Core\Exception\Validation')
            ->duringValidate();
    }
}
