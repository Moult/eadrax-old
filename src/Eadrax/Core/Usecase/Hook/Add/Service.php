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
    public $url;
    private $validator;

    public function __construct(Data\Hook $hook, Tool\Validator $validator)
    {
        $this->url = $hook->url;
        $this->validator = $validator;
    }

    public function is_valid()
    {
        $this->validator->setup(array('url' => $this->url));
        $this->validator->rule('url', 'rss2');
        if ( ! $this->validator->check())
            throw new Exception\Validation($this->validator->errors());
    }
}
