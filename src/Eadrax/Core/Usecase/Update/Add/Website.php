<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Website extends Data\Website implements Proposal
{
    public $project;
    public $private;
    public $thumbnail;
    public $url;
    private $repository;
    private $browser;
    private $photoshopper;
    private $validator;

    public function __construct(Data\Website $website, Repository $repository, Tool\Browser $browser, Tool\Photoshopper $photoshopper, Tool\Validator $validator)
    {
        $this->project = $website->project;
        $this->private = $website->private;
        $this->url = $this->normalise_url($website->url);
        $this->repository = $repository;
        $this->browser = $browser;
        $this->photoshopper = $photoshopper;
        $this->validator = $validator;
    }

    private function normalise_url($url)
    {
        return ((substr($url, 0, 7) !== 'http://') ? 'http://' : '').$url;
    }

    public function validate()
    {
        $this->validator->setup(array('url' => $this->url));
        $this->validator->rule('url', 'not_empty');
        $this->validator->rule('url', 'url');
        $this->validator->rule('url', 'url_domain');

        if ( ! $this->validator->check())
            throw new Exception\Validation($this->validator->errors());
    }

    public function submit()
    {
        $this->id = $this->repository->save_website(
            $this->project->id,
            $this->private,
            $this->url,
            $this->repository->save_generated_file($this->thumbnail)
        );
    }

    public function get_id()
    {
        return $this->id;
    }

    public function generate_thumbnail()
    {
        $screenshot_path = $this->browser->screenshot($this->url);
        $this->photoshopper->setup($screenshot_path);
        $this->photoshopper->resize_to_height(100);
        $this->thumbnail = $screenshot_path;
    }
}
