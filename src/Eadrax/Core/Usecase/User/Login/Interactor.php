<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Login;

class Interactor
{
    private $guest;

    public function __construct(Guest $guest)
    {
        $this->guest = $guest;
    }

    public function interact()
    {
        $this->guest->authorise();
        $this->guest->validate();
        $this->guest->login();
        return $this->guest->id;
    }
}
