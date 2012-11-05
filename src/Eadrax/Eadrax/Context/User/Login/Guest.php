<?php
/**
 * Eadrax Context/User/Login/Guest.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\User\Login;
use Eadrax\Eadrax\Context;
use Eadrax\Eadrax\Model;
use Eadrax\Eadrax\Entity;

/**
 * Allows model_user to be cast as a guest role
 *
 * @package    Context
 * @subpackage Role
 */ 
class Guest extends Model\User implements Guest\Requirement
{
    use Context\Interaction, Guest\Interaction;

    /**
     * Takes a data object and copies all of its properties
     *
     * @param Model\User        $model_user        Data object to copy
     * @param Repository        $repository        Repository
     * @param Entity\Auth       $entity_auth       Authentication entity
     * @param Entity\Validation $entity_validation Validation entity
     * @return void
     */
    public function __construct(Model\User $model_user = NULL, Repository $repository = NULL, Entity\Auth $entity_auth = NULL, Entity\Validation $entity_validation = NULL)
    {
        if ($model_user !== NULL)
        {
            $this->assign_data($model_user);
        }

        $links = array();
        if ($repository !== NULL)
        {
            $links['repository'] = $repository;
        }

        if ($entity_auth !== NULL)
        {
            $links['entity_auth'] = $entity_auth;
        }

        if ($entity_validation !== NULL)
        {
            $links['entity_validation'] = $entity_validation;
        }

        $this->link($links);
    }

    /**
     * Assigns data into the role from a data object
     *
     * @param Model\User $model_user Data object to copy
     * @return void
     */
    public function assign_data(Model\User $model_user)
    {
        parent::__construct(get_object_vars($model_user));
    }
}
