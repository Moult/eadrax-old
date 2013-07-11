<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Track\User;
use Eadrax\Core\Usecase;

class Track
{
    public $data;
    private $repositories;
    private $tools;

    public function __construct(array $data, array $repositories, array $tools)
    {
        $this->data = $data;
        $this->repositories = $repositories;
        $this->tools = $tools;
    }

    public function set_author_id($author_id)
    {
        $this->data['user']->id = $author_id;
    }

    public function fetch()
    {
        $user_track = new Usecase\User\Track($this->data, $this->repositories, $this->tools);
        return $user_track->fetch();
    }
}
