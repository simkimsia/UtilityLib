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
 * @version 0.2
 * @lastmodified 2013-03-14
 */
class ArrayLib {
	
/**
 * 
 * resursively run the ksort
 *
 * @param array $array
 * @return array
 */
	public static function deepKSort(&$array) {
		ksort($array);
		foreach ($array as &$a) {
			if (is_array($a) && !empty($a)) {
				self::deepKSort($a);
			}
		}
	}

/**
 * 
 * insert value at any point inside a numerically indexed array
 * inspired by http://stackoverflow.com/a/3797526/80353
 * @param array $data Array to insert $value in
 * @param int $index Position to insert at. Assumed zero based index.
 * @param mixed $value  Value to insert array with.
 * @return array $data array with the new $value inserted.
 */
	public static function insert(array $data, int $index, $value) {
		array_splice( $data, $index, 0, $value); // splice in at position 3
		return $data;
	}
}