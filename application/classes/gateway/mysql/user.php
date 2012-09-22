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
        $auth_config = Kohana::$config->load('auth');
        $password_hash = hash_hmac($auth_config->get('hash_method'), $data['password'], $auth_config->get('hash_key'));
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
     * @return bool
     */
    public function exists($data)
    {
        $query = DB::select('id')->from($this->table)->limit(1);
        foreach ($data as $field => $value)
        {
            $query->where($field, '=', $value);
        }
        return (bool) $query->execute()->count();
    }
}
