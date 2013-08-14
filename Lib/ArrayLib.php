<?php
/**
 * Utility Library is the utility belt of useful functions
 * http://github.com/simkimsia/UtilityLib/
 *
 * Array Utility Library
 *
 * This Utility Library is for array manipulation.
 *
 * Copyright 2013, Kim Stacks
 * Singapore
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2013, Kim Stacks.
 * @link http://stacktogether.com
 * @author Kim Stacks <kim@stacktogether.com>
 * @package app
 * @subpackage app.Lib
 * @filesource
 * @version 0.3
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @lastmodified 2013-07-09
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

/**
 * Check the keys of an array against a list of values. Returns true if all values in the list
 is not in the array as a key. Returns false otherwise.
 *
 * @param $array Associative array with keys and values
 * @param $mustHaveKeys Array whose values contain the keys that MUST exist in $array
 * @param &$missingKeys Array. Pass by reference. An array of the missing keys in $array as string values.
 * @return Boolean. Return true only if all the values in $mustHaveKeys appear in $array as keys.
 */
	public static function checkIfKeysExist($array, $mustHaveKeys, &$missingKeys = array()) {
		// extract the keys of $array as an array
		$keys = array_keys($array);
		// ensure the keys we look for are unique
		$mustHaveKeys = array_unique($mustHaveKeys);
		// $missingKeys = $mustHaveKeys - $keys
		// we expect $missingKeys to be empty if all goes well
		$missingKeys = array_diff($mustHaveKeys, $keys);
		return empty($missingKeys);
	}

/**
 * Extract the key-value pairs of an array against a list of keys.
 *
 * @param $array Associative array with keys and values
 * @param $shouldHaveKeys Array whose values contain the keys that SHOULD exist in $array
 * @param &$missingKeys Array. Pass by reference. An array of the missing keys in $array as string values.
 * @return Array. Return an array of the key-value pairs
 */
	public static function extractIfKeysExist($array, $shouldHaveKeys, &$missingKeys = array()) {
		// ensure the keys we look for are unique
		$shouldHaveKeys = array_unique($shouldHaveKeys);
		$extractedData = array_intersect_key($array, array_flip($shouldHaveKeys));

		// extract the keys of $array as an array
		$keys = array_keys($array);
		// $missingKeys = $mustHaveKeys - $keys
		$missingKeys = array_diff($shouldHaveKeys, $keys);
		return $extractedData;
	}

/**
 * Convert stdClass objects to multi-dimensional arrays
 *
 * @author JR
 * @link http://www.if-not-true-then-false.com/2009/php-tip-convert-stdclass-object-to-multidimensional-array-and-convert-multidimensional-array-to-stdclass-object/
 * @param mixed $d
 * @return Array
 */
	public static function objectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map( array('ArrayLib','objectToArray') , $d);
		}
		else {
			// Return array
			return $d;
		}
	}

/**
 * Convert multi-dimensional arrays to stdClass objects
 *
 * @author JR
 * @link http://www.if-not-true-then-false.com/2009/php-tip-convert-stdclass-object-to-multidimensional-array-and-convert-multidimensional-array-to-stdclass-object/
 * @param mixed $d
 * @return object
 */
	public static function arrayToObject($d) {
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return (object) array_map(self::arrayToObject($d));
		}
		else {
			// Return object
			return $d;
		}
	}

}