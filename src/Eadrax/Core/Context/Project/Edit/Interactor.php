<?php
/**
 * Eadrax Context/Project/Edit/Interactor.php
 *
 * @package   Interactor
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult|Omni Studios
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Context\Project\Edit;
use Eadrax\Core\Context\Project\Edit\User;
use Eadrax\Core\Exception;

class Interactor
{
    /**
     * User role
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function interact()
    {
        $this->user->authorise_project_edit();
        $this->user->check_proposal_author();
    }

    public function execute()
    {
        try
        {
            $this->interact();
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
