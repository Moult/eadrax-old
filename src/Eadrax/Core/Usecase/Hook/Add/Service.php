<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Hook\Add;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Service extends Data\Hook
{
    public function __construct(Data\Hook $hook, Tool\Validation $validation)
    {
        $this->url = $hook->url;
        $this->validation = $validation;
    }

    public function is_valid()
    {
        $this->validation->setup(array('url' => $this->url));
        $this->validation->rule('url', 'rss2');
        if ( ! $this->validation->check())
            throw new Exception\Validation($this->validation->errors());
    }
}
