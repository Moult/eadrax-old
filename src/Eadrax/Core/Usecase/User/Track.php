<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User;
use Eadrax\Core\Usecase\User\Track\Interactor;
use Eadrax\Core\Usecase\User\Track\Idol;
use Eadrax\Core\Usecase\User\Track\Fan;

class Track
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
            $this->get_idol(),
            $this->get_fan()
        );
    }

    private function get_idol()
    {
        return new Idol(
            $this->data['user'],
            $this->repositories['user_track'],
            $this->tools['emailer'],
            $this->tools['formatter']
        );
    }

    private function get_fan()
    {
        return new Fan(
            $this->repositories['user_track'],
            $this->tools['authenticator']
        );
    }
}
