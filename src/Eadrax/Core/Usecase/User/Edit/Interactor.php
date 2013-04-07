<?php

namespace Eadrax\Core\Usecase\User\Edit;

class Interactor
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function interact()
    {
        $this->user->authorise();
        $this->user->validate();
        $this->user->update();
    }
}
