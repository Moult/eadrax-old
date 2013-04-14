<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PHPSpec2\ObjectBehavior;

class Proposal extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Update $update
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Tool\Filesystem $filesystem
     * @param Eadrax\Core\Tool\Image $image
     * @param Eadrax\Core\Tool\Upload $upload
     * @param Eadrax\Core\Tool\Validation $validation
     */
    public function let($update, $project, $filesystem, $image, $upload, $validation)
    {
        $update->type = 'type';
        $update->content = 'content';
        $update->extra = 'extra';
        $update->private = 'private';
        $update->project = $project;
        $this->beConstructedWith($update, $filesystem, $image, $upload, $validation);
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
        $validation->rule('content', 'not_empty')->shouldBeCalled();
        $validation->rule('content', 'upload_valid')->shouldBeCalled();
        $validation->rule('content', 'upload_type', $supported_filetypes)->shouldBeCalled();
        $validation->rule('content', 'upload_size', '100M')->shouldBeCalled();
        $validation->check()->shouldBeCalled();
        $validation->errors()->shouldBeCalled()->willReturn(array('content'));
        $this->shouldThrow('Eadrax\Core\Exception\Validation')
            ->duringValidate();
    }

    /**
     * @param Eadrax\Core\Data\File $file
     */
    function it_detects_image_file_type($file, $update)
    {
        $file->name = 'foo.png';
        $update->type = 'file';
        $update->content = $file;
        $this->detect_file_type();
        $this->type->shouldBe('file/image');
    }

    /**
     * @param Eadrax\Core\Data\File $file
     */
    function it_detects_video_file_type($file, $update)
    {
        $file->name = 'foo.avi';
        $update->type = 'file';
        $update->content = $file;
        $this->detect_file_type();
        $this->type->shouldBe('file/video');
    }

    /**
     * @param Eadrax\Core\Data\File $file
     */
    function it_detects_sound_file_type($file, $update)
    {
        $file->name = 'foo.mp3';
        $update->type = 'file';
        $update->content = $file;
        $this->detect_file_type();
        $this->type->shouldBe('file/sound');
    }

    /**
     * @param Eadrax\Core\Data\File $file
     */
    function it_can_upload_files($upload, $update, $file)
    {
        $update->content = $file;
        $upload->save($file, '/path/to/upload')->shouldBeCalled()->willReturn('/path/to/upload/file');
        $this->upload();
        $this->content->shouldBe('/path/to/upload/file');
    }

    /**
     * @param Eadrax\Core\Data\File $file
     */
    function it_encodes_uploaded_videos($filesystem, $upload, $update, $file)
    {
        $update->type = 'file/video';
        $update->content = '/path/to/upload/file.avi';
        $filesystem->encode_video_to_webm('/path/to/upload/file.avi')->shouldBeCalled();
        $this->encode_video();
    }

    function it_generates_metadata_for_images($update, $filesystem)
    {
        $update->type = 'file/image';
        $update->content = '/path/to/upload/file.png';
        $filesystem->get_image_dimensions('/path/to/upload/file.png')->shouldBeCalled()
            ->willReturn(array(200, 300));
        $filesystem->get_file_size('/path/to/upload/file.png')->shouldBeCalled()
            ->willReturn(12345);
        $this->generate_metadata();
        $this->extra->shouldBe('a:3:{s:6:"height";i:300;s:5:"width";i:200;s:4:"size";i:12345;}');
    }

    function it_generates_metadata_for_videos($update, $filesystem)
    {
        $update->type = 'file/video';
        $update->content = '/path/to/upload/file.avi';
        $filesystem->get_video_dimensions('/path/to/upload/file.avi')->shouldBeCalled()
            ->willReturn(array(200, 300));
        $filesystem->get_video_length('/path/to/upload/file.avi')->shouldBeCalled()
            ->willReturn(123);
        $filesystem->get_file_size('/path/to/upload/file.avi')->shouldBeCalled()
            ->willReturn(12345);
        $this->generate_metadata();
        $this->extra->shouldBe('a:4:{s:6:"height";i:300;s:5:"width";i:200;s:6:"length";i:123;s:4:"size";i:12345;}');
    }

    function it_generates_metadata_for_sound($update, $filesystem)
    {
        $update->type = 'file/sound';
        $update->content = '/path/to/upload/file.mp3';
        $filesystem->get_sound_length('/path/to/upload/file.mp3')->shouldBeCalled()
            ->willReturn(123);
        $filesystem->get_file_size('/path/to/upload/file.mp3')->shouldBeCalled()
            ->willReturn(12345);
        $this->generate_metadata();
        $this->extra->shouldBe('a:2:{s:6:"length";i:123;s:4:"size";i:12345;}');
    }

    function it_generates_metadata_for_binary_files($update, $filesystem)
    {
        $update->type = 'file';
        $update->content = '/path/to/upload/file.zip';
        $filesystem->get_file_size('/path/to/upload/file.zip')->shouldBeCalled()
            ->willReturn(12345);
        $this->generate_metadata();
        $this->extra->shouldBe('a:1:{s:4:"size";i:12345;}');
    }

    function it_generates_thumbnails_for_websites($update, $image)
    {
        $update->type = 'website';
        $update->content = 'http://foo.com';
        $image->screenshot_website('http://foo.com', '/path/to/thumbnail/foo.com.png')->shouldBeCalled();
        $this->generate_thumbnail();
    }

    function it_generates_thumbnails_for_images($update, $image)
    {
        $update->type = 'file/image';
        $update->content = 'foo.jpg';
        $image->thumbnail_image('foo.jpg', '/path/to/thumbnail/foo.jpg.png')->shouldBeCalled();
        $this->generate_thumbnail();
    }

    function it_generates_thumbnails_for_videos($update, $image)
    {
        $update->type = 'file/video';
        $update->content = 'foo.avi';
        $image->thumbnail_video('foo.avi', '/path/to/thumbnail/foo.avi.png')->shouldBeCalled();
        $this->generate_thumbnail();
    }

    function it_generates_thumbnails_for_sounds($update, $image)
    {
        $update->type = 'file/sound';
        $update->content = 'foo.mp3';
        $image->thumbnail_sound('foo.mp3', '/path/to/thumbnail/foo.mp3.png')->shouldBeCalled();
        $this->generate_thumbnail();
    }

}
