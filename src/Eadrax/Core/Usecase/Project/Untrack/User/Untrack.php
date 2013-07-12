<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Untrack\User;
use Eadrax\Core\Usecase;

class Untrack
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

    public function fetch()
    {
        $usecase = new Usecase\User\Untrack($this->data, $this->repositories, $this->tools);
        return $usecase->fetch();
    }

    public function set_author_id($author_id)
    {
        $this->data['user']->id = $author_id;
    }
}
