<?php
/**
 * Eadrax application/classes/gateway/mysql/user.php
 *
 * @package   Gateway
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Handles MySQL interactions with the user table
 *
 * @package    Gateway
 * @subpackage MySQL
 */
class Gateway_Mysql_User {
    /**
     * Database table name
     * @var string
     */
    private $table = 'users';

    /**
     * Inserts a new row.
     *
     * @return void
     */
    public function insert($data)
    {
        $query = DB::insert($this->table, array(
            'username',
            'password',
            'email'
        ))->values(array(
            $data['username'],
            $data['password'],
            $data['email']
        ));
        $query->execute();
    }
}
