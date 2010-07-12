<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Validation helper class.
 *
 * $Id: valid.php 4367 2009-05-27 21:23:57Z samsoir $
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class valid extends valid_Core {
	/**
	 * Validate integers between a range of values (inclusive).
	 *
	 * @param  int    $int
	 * @param  array  $range
	 * @return  boolean
	 */
	public static function between($int, array $range)
	{
	  return is_numeric($int) && $int >= $range[0] && $int <= $range[1];
	}

} // End valid
