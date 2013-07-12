<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User;
use Eadrax\Core\Usecase\User\Untrack\Interactor;
use Eadrax\Core\Usecase\User\Untrack\Fan;
use Eadrax\Core\Usecase\User\Untrack\Idol;

class Untrack
{
    private $data;
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
        return new Interactor(
            $this->get_fan(),
            $this->get_idol()
        );
    }

    private function get_fan()
    {
        return new Fan(
            $this->tools['authenticator']
        );
    }

    private function get_idol()
    {
        return new Idol(
            $this->data['user'],
            $this->repositories['user_untrack'],
            $this->tools['authenticator']
        );
    }
}
