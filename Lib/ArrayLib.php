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
 * @package UtilityLib
 * @subpackage UtilityLib.Lib
 * @filesource
 * @version 0.4
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @lastmodified 2013-09-28
 */
App::uses('Hash', 'Utility');
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
			return (object) array_map(array('ArrayLib','arrayToObject') , $d);
		}
		else {
			// Return object
			return $d;
		}
	}

/**
 *
 * Take in either model.n or n.model and extract a preferred nested format also in
 either model.n or model.n format
 * @param $data array of original search results
 * @param $options array. Keys are:
  - from Required
  - to Optional. Default value is the reverse of `from`
 * @return array Extracted array following the desired to indicated in $options
 */
	public static function extractToNest($data, $options = array()) {
		if (!isset($options['from'])) {
			throw new Exception('Compulsory to set the `from` in $options', 1);
		}
		$dotPositionInFrom	= strpos($options['from'], '.');
		$nPositionInFrom	= strpos($options['from'], '{n}');
		$fromLength			= strlen($options['from']);
		if ($dotPositionInFrom === false || $dotPositionInFrom === 0 || $nPositionInFrom === false) {
			throw new Exception('Expected `from` in $options to be either {n}.ModelName or ModelName.{n}.' . $options['from'] . 'is neither.', 1);
		}
		if (isset($options['to'])) {
			$dotPosition = strpos($options['to'], '.');
			$nPositionInTo = strpos($options['to'], '{n}');
			if ($dotPosition === false || $dotPosition === 0 || $nPositionInTo === false) {
				throw new Exception('Expected `to` in $options to be either {n}.ModelName or ModelName.{n}.' . $options['to'] . 'is neither.', 1);
			}
		} else {
			// we reverse $options['from'] to derive the $options['to'] if unknown
			$tokenBeforeDot	= substr($options['from'], 0, $dotPositionInFrom);
			$tokenAfterDot	= substr($options['from'], $dotPositionInFrom, $fromLength);
			if ($tokenBeforeDot != '{n}' && $tokenAfterDot != '{n}' ) {
				throw new Exception('Expected `from` in $options to be either {n}.ModelName or ModelName.{n}.' . $options['from'] . 'is neither.', 1);
			}
			$options['to'] = "$tokenAfterDot.$tokenBeforeDot";
			$nPositionInTo = strpos($options['to'], '{n}');
		}
		$destinationFormatIsNDotModel	= ($nPositionInTo === 0);
		$destinationFormatIsModelDotN	= ($nPositionInTo > 0);
		$sourceFormatIsNDotModel		= ($nPositionInFrom === 0);
		$sourceFormatIsModelDotN		= ($nPositionInFrom > 0);
		$toLength						= strlen($options['to']);

		if ($destinationFormatIsNDotModel) {
			$destinationModelName	= substr($options['to'], $dotPosition + 1);
			$results				= Hash::map($data, $options['from'], function($child) use ($destinationModelName) {
				return array($destinationModelName => $child);
			});
			return $results;
		}
		if ($destinationFormatIsModelDotN) {
			$destinationModelName			= substr($options['to'], 0, $dotPosition);
			$results						= array($destinationModelName => array());
			$results[$destinationModelName]	= Hash::map($data, $options['from'], function($child) use ($destinationModelName) {
				return $child;
			});
			return $results;
		}
	}

}