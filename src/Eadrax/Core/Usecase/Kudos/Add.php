<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Kudos;
use Eadrax\Core\Usecase\Kudos\Add\Interactor;
use Eadrax\Core\Usecase\Kudos\Add\Nomination;

class Add
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
            $this->get_nomination()
        );
    }

    private function get_nomination()
    {
        return new Nomination(
            $this->data['update'],
            $this->repositories['kudos_add'],
            $this->tools['authenticator'],
            $this->tools['emailer'],
            $this->tools['formatter']
        );
    }
}
