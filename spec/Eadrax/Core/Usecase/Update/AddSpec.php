<?php

namespace spec\Eadrax\Core\Usecase\Update;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AddSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Data\Text $text
     * @param Eadrax\Core\Usecase\Update\Add\Repository $update_add
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Emailer $emailer
     * @param Eadrax\Core\Tool\Formatter $formatter
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($project, $text, $update_add, $authenticator, $emailer, $formatter, $validator)
    {
        $data = array(
            'project' => $project,
            'update' => $text
        );

        $repositories = array(
            'update_add' => $update_add
        );

        $tools = array(
            'authenticator' => $authenticator,
            'emailer' => $emailer,
            'formatter' => $formatter,
            'validator' => $validator
        );

        $this->beConstructedWith($data, $repositories, $tools);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add');
    }

    function it_fetches_the_interactor_with_text_data()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Interactor');
    }

    /**
     * @param Eadrax\Core\Data\Paste $paste
     */
    function it_fetches_the_interactor_with_paste_data($project, $paste, $update_add, $authenticator, $emailer, $formatter, $validator)
    {
        $data = array(
            'project' => $project,
            'update' => $paste
        );

        $repositories = array(
            'update_add' => $update_add
        );

        $tools = array(
            'authenticator' => $authenticator,
            'emailer' => $emailer,
            'formatter' => $formatter,
            'validator' => $validator
        );

        $this->beConstructedWith($data, $repositories, $tools);
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Interactor');
    }

    /**
     * @param Eadrax\Core\Data\Image $image
     * @param Eadrax\Core\Tool\Photoshopper $photoshopper
     */
    function it_fetches_the_interactor_with_image_data($project, $image, $update_add, $authenticator, $emailer, $formatter, $validator, $photoshopper)
    {
        $data = array(
            'project' => $project,
            'update' => $image
        );

        $repositories = array(
            'update_add' => $update_add
        );

        $tools = array(
            'authenticator' => $authenticator,
            'emailer' => $emailer,
            'formatter' => $formatter,
            'validator' => $validator,
            'photoshopper' => $photoshopper
        );

        $this->beConstructedWith($data, $repositories, $tools);
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Interactor');
    }

    /**
     * @param Eadrax\Core\Data\Sound $sound
     * @param Eadrax\Core\Tool\Filemanager $filemanager
     * @param Eadrax\Core\Tool\Soundeditor $soundeditor
     */
    function it_fetches_the_interactor_with_sound_data($project, $sound, $update_add, $authenticator, $emailer, $formatter, $validator, $filemanager, $soundeditor)
    {
        $data = array(
            'project' => $project,
            'update' => $sound
        );

        $repositories = array(
            'update_add' => $update_add
        );

        $tools = array(
            'authenticator' => $authenticator,
            'emailer' => $emailer,
            'formatter' => $formatter,
            'validator' => $validator,
            'filemanager' => $filemanager,
            'soundeditor' => $soundeditor
        );

        $this->beConstructedWith($data, $repositories, $tools);
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Interactor');
    }

    /**
     * @param Eadrax\Core\Data\Video $video
     * @param Eadrax\Core\Tool\Filemanager $filemanager
     * @param Eadrax\Core\Tool\Videoeditor $videoeditor
     */
    function it_fetches_the_interactor_with_video_data($project, $video, $update_add, $authenticator, $emailer, $formatter, $validator, $filemanager, $videoeditor)
    {
        $data = array(
            'project' => $project,
            'update' => $video
        );

        $repositories = array(
            'update_add' => $update_add
        );

        $tools = array(
            'authenticator' => $authenticator,
            'emailer' => $emailer,
            'formatter' => $formatter,
            'validator' => $validator,
            'filemanager' => $filemanager,
            'videoeditor' => $videoeditor
        );

        $this->beConstructedWith($data, $repositories, $tools);
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Interactor');
    }

    /**
     * @param Eadrax\Core\Data\Website $website
     * @param Eadrax\Core\Tool\Browser $browser
     * @param Eadrax\Core\Tool\Photoshopper $photoshopper
     */
    function it_fetches_the_interactor_with_website_data($project, $website, $update_add, $authenticator, $emailer, $formatter, $validator, $browser, $photoshopper)
    {
        $data = array(
            'project' => $project,
            'update' => $website
        );

        $repositories = array(
            'update_add' => $update_add
        );

        $tools = array(
            'authenticator' => $authenticator,
            'emailer' => $emailer,
            'formatter' => $formatter,
            'validator' => $validator,
            'browser' => $browser,
            'photoshopper' => $photoshopper
        );

        $this->beConstructedWith($data, $repositories, $tools);
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Interactor');
    }
}
