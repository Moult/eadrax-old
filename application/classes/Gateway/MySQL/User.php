<?php
/**
 * Eadrax application/classes/Gateway/MySQL/User.php
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
class Gateway_MySQL_User {
    /**
     * Database table name
     * @var string
     */
    private $table = 'users';

    /**
     * Inserts a new row.
     *
     * @param array $data An array with keys for username, password, and email
     * @return void
     */
    public function insert($data)
    {
        $password_hash = Auth::instance()->hash($data['password']);
        $query = DB::insert($this->table, array(
            'username',
            'password',
            'email'
        ))->values(array(
            $data['username'],
            $password_hash,
            $data['email']
        ));
        $query->execute();
    }

    /**
     * Checks whether such a row exists.
     *
     * @param array $data Holds search criteria in the form of $field => $value
     * @return bool
     */
    public function exists($data)
    {
        $query = DB::select('id')->from($this->table)->limit(1);
        foreach ($data as $field => $value)
        {
            if ($field === 'password')
            {
                $value = Auth::instance()->hash($value);
            }

            $query->where($field, '=', $value);
        }
        return (bool) $query->execute()->count();
    }
}
