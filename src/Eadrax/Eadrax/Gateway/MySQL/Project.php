<?php
/**
 * Eadrax Gateway/MySQL/Project.php
 *
 * @package   Gateway
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Gateway\MySQL;

/**
 * Handles MySQL interactions with the project table
 *
 * @package    Gateway
 * @subpackage MySQL
 */
class Project {
    /**
     * Database table name
     * @var string
     */
    private $table = 'projects';

    /**
     * Inserts a new row.
     *
     * @param array $data An array with keys for each field
     * @return void
     */
    public function insert($data)
    {
        $query = DB::insert($this->table, array(
            'name',
            'summary',
            'uid'
        ))->values(array(
            $data['name'],
            $data['summary'],
            $data['uid']
        ));
        $query->execute();
    }
}
