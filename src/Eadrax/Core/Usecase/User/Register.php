<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User;

use Eadrax\Core\Usecase;
use Eadrax\Core\Usecase\User;
use Eadrax\Core\Usecase\User\Register\Interactor;
use Eadrax\Core\Usecase\User\Register\Registrant;

class Register
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
            $this->get_registrant(),
            $this->get_user_login()
        );
    }

    private function get_registrant()
    {
        return new Registrant(
            $this->data['user'],
            $this->repositories['user_register'],
            $this->tools['auth'],
            $this->tools['validation']
        );
    }

    private function get_user_login()
    {
        return new User\Login\Interactor(
            $this->get_user_login_guest()
        );
    }

    private function get_user_login_guest()
    {
        return new User\Login\Guest(
            $this->data['user'],
            $this->repositories['user_login'],
            $this->tools['auth'],
            $this->tools['validation']
        );
    }
}
