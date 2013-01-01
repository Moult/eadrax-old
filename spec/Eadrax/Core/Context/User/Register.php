<?php

namespace spec\Eadrax\Core\Context\User;

require_once 'spec/Eadrax/Core/Context/Core.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context\Core;

class Register extends ObjectBehavior
{
    use Core;

    /**
     * @param Eadrax\Core\Data\User                        $data_user
     * @param Eadrax\Core\Context\User\Register\Repository $repository
     * @param Eadrax\Core\Context\User\Login\Repository    $repository_user_login
     * @param Eadrax\Core\Entity\Auth                      $entity_auth
     * @param Eadrax\Core\Entity\Validation                $entity_validation
     */
    function let($data_user, $repository, $repository_user_login, $entity_auth, $entity_validation)
    {
        $this->beConstructedWith($data_user, $repository, $repository_user_login, $entity_auth, $entity_validation);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\User\Register');
    }

    function it_should_be_a_context()
    {
        $this->shouldHaveType('Eadrax\Core\Context\Core');
    }

    function it_should_fetch_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Context\User\Register\Interactor');
    }
}
