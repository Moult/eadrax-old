<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User;

use Eadrax\Core\Usecase\User\Login\Interactor;
use Eadrax\Core\Usecase\User\Login\Guest;
use Eadrax\Core\Data;

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
            $this->get_user(),
            $this->repositories['user_login'],
            $this->tools['auth'],
            $this->tools['validation']
        );
    }

    private function get_user()
    {
        $user = new Data\User;
        $user->username = $this->data['username'];
        $user->password = $this->data['password'];
        return $user;
    }
}
