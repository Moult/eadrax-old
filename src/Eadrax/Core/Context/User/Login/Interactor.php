<?php
/**
 * Eadrax Context/User/Login/Interactor.php
 *
 * @package   Interactor
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Context\User\Login;
use Eadrax\Core\Exception;

/**
 * Runs the user login usecase
 *
 * @package Interactor
 */
class Interactor
{
    /**
     * Guest role
     * @var Guest
     */
    private $guest;

    /**
     * Sets up role dependencies
     *
     * @return void
     */
    public function __construct(Guest $guest)
    {
        $this->guest = $guest;
    }

    /**
     * Executes the interaction chain
     *
     * @return void
     */
    public function interact()
    {
        $this->guest->authorise_login();
        $this->guest->validate_information();
        $this->guest->login();
    }

    /**
     * Run interaction, generating a results array.
     *
     * @return array
     */
    public function execute()
    {
        try
        {
            $this->interact();
        }
        catch (Exception\Validation $e)
        {
            return array(
                'status' => 'failure',
                'type' => 'validation',
                'data' => array(
                    'errors' => $e->get_errors()
                )
            );
        }
        catch (Exception\Authorisation $e)
        {
            return array(
                'status' => 'failure',
                'type' => 'authorisation',
                'data' => array(
                    'errors' => array($e->getMessage())
                )
            );
        }

        return array(
            'status' => 'success'
        );
    }
}
