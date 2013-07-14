<?php

namespace spec\Eadrax\Core\Usecase\Update;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PrepareSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Text $text
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Emailer $emailer
     * @param Eadrax\Core\Tool\Formatter $formatter
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($text, $authenticator, $emailer, $formatter, $validator)
    {
        $data = array(
            'update' => $text
        );

        $tools = array(
            'authenticator' => $authenticator,
            'emailer' => $emailer,
            'formatter' => $formatter,
            'validator' => $validator
        );

        $this->beConstructedWith($data, $tools);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Prepare');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Update\Prepare\Interactor');
    }

    /**
     * @param Eadrax\Core\Data\Paste $paste
     */
    function it_fetches_the_interactor_with_paste_data($paste, $authenticator, $emailer, $formatter, $validator)
    {
        $data = array(
            'update' => $paste
        );

        $tools = array(
            'authenticator' => $authenticator,
            'emailer' => $emailer,
            'formatter' => $formatter,
            'validator' => $validator
        );

        $this->beConstructedWith($data, $tools);
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Update\Prepare\Interactor');
    }

    /**
     * @param Eadrax\Core\Data\Image $image
     * @param Eadrax\Core\Tool\Photoshopper $photoshopper
     */
    function it_fetches_the_interactor_with_image_data($image, $authenticator, $emailer, $formatter, $validator, $photoshopper)
    {
        $data = array(
            'update' => $image
        );

        $tools = array(
            'authenticator' => $authenticator,
            'emailer' => $emailer,
            'formatter' => $formatter,
            'validator' => $validator,
            'photoshopper' => $photoshopper
        );

        $this->beConstructedWith($data, $tools);
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Update\Prepare\Interactor');
    }

    /**
     * @param Eadrax\Core\Data\Sound $sound
     * @param Eadrax\Core\Tool\Filemanager $filemanager
     * @param Eadrax\Core\Tool\Soundeditor $soundeditor
     */
    function it_fetches_the_interactor_with_sound_data($sound, $authenticator, $emailer, $formatter, $validator, $filemanager, $soundeditor)
    {
        $data = array(
            'update' => $sound
        );

        $tools = array(
            'authenticator' => $authenticator,
            'emailer' => $emailer,
            'formatter' => $formatter,
            'validator' => $validator,
            'filemanager' => $filemanager,
            'soundeditor' => $soundeditor
        );

        $this->beConstructedWith($data, $tools);
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Update\Prepare\Interactor');
    }

    /**
     * @param Eadrax\Core\Data\Video $video
     * @param Eadrax\Core\Tool\Filemanager $filemanager
     * @param Eadrax\Core\Tool\Videoeditor $videoeditor
     */
    function it_fetches_the_interactor_with_video_data($video, $authenticator, $emailer, $formatter, $validator, $filemanager, $videoeditor)
    {
        $data = array(
            'update' => $video
        );

        $tools = array(
            'authenticator' => $authenticator,
            'emailer' => $emailer,
            'formatter' => $formatter,
            'validator' => $validator,
            'filemanager' => $filemanager,
            'videoeditor' => $videoeditor
        );

        $this->beConstructedWith($data, $tools);
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Update\Prepare\Interactor');
    }

    /**
     * @param Eadrax\Core\Data\Website $website
     * @param Eadrax\Core\Tool\Browser $browser
     * @param Eadrax\Core\Tool\Photoshopper $photoshopper
     */
    function it_fetches_the_interactor_with_website_data($website, $authenticator, $emailer, $formatter, $validator, $browser, $photoshopper)
    {
        $data = array(
            'update' => $website
        );

        $tools = array(
            'authenticator' => $authenticator,
            'emailer' => $emailer,
            'formatter' => $formatter,
            'validator' => $validator,
            'browser' => $browser,
            'photoshopper' => $photoshopper
        );

        $this->beConstructedWith($data, $tools);
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Update\Prepare\Interactor');
    }
}
