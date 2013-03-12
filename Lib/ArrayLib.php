<?php
/**
 * Utility Library is the utility belt of useful functions
 * http://github.com/simkimsia/UtilityLib/
 *
 * Array Utility Library
 *
 * This Utility Library is for array manipulation.
 *
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2011, Sim Kim Sia
 * @link http://simkimsia.com
 * @author Sim Kim Sia (kimcity@gmail.com)
 * @package app
 * @subpackage app.Lib
 * @filesource
 * @version 0.1
 * @lastmodified 2011-10-03 
 */
class ArrayLib {
	
	/**
	* 
	* resursively run the ksort
	*
	* @param array $array
	* @return array
	**/
	public static function deepKSort(&$array) {
	    ksort($array);
	    foreach ($array as &$a) {
	        if (is_array($a) && !empty($a)) {
	            self::deepKSort($a);
	        }
	    }
	}
}
?>