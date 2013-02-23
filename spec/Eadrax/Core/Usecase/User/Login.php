<?php

namespace spec\Eadrax\Core\Usecase\User;

require_once 'spec/Eadrax/Core/Usecase/Core.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Usecase\Core;

class Login extends ObjectBehavior
{
    use Core;

    /**
     * @param Eadrax\Core\Data\User                    $data_user
     * @param Eadrax\Core\Usecase\User\Login\Repository $repository
     * @param Eadrax\Core\Tool\Auth                   $entity_auth
     * @param Eadrax\Core\Tool\Validation             $entity_validation
     */
    function let($data_user, $repository, $entity_auth, $entity_validation)
    {
        $this->beConstructedWith($data_user, $repository, $entity_auth, $entity_validation);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Login');
    }

    function it_should_be_a_context()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Core');
    }

    function it_should_fetch_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\User\Login\Interactor');
    }
}
