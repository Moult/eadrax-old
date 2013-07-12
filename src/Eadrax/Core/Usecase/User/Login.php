<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User;

use Eadrax\Core\Usecase\User\Login\Interactor;
use Eadrax\Core\Usecase\User\Login\Guest;

class Login
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
        return new Interactor($this->get_guest());
    }

    private function get_guest()
    {
        return new Guest(
            $this->data['user'],
            $this->repositories['user_login'],
            $this->tools['authenticator'],
            $this->tools['validator']
        );
    }
}
