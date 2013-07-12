<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User;
use Eadrax\Core\Usecase\User\Edit\Interactor;
use Eadrax\Core\Usecase\User\Edit\User;

class Edit
{
    private $data;
    private $repositories;
    private $tools;

    public function __construct(Array $data, Array $repositories, Array $tools)
    {
        $this->data = $data;
        $this->repositories = $repositories;
        $this->tools = $tools;
    }

    public function fetch()
    {
        return new Interactor(
            $this->get_user()
        );
    }

    private function get_user()
    {
        return new User(
            $this->data['user'],
            $this->repositories['user_edit'],
            $this->tools['authenticator'],
            $this->tools['validator']
        );
    }
}
