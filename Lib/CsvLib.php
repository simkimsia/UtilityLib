<?php
/**
 * Utility Library is the utility belt of useful functions
 * http://github.com/simkimsia/UtilityLib/
 *
 * Csv Utility Library
 *
 * This Utility Library is for manipulation for a csv file
 *
 * Copyright 2013, Kim Stacks
 * Singapore
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2011-2013, Kim Stacks.
 * @link http://stacktogether.com
 * @author Kim Stacks <kim@stacktogether.com>
 * @package UtilityLib
 * @subpackage UtilityLib.Lib
 * @filesource
 * @version 0.1
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @lastmodified 2012-02-04
 */

class CsvLib {

	const QUOTE = "\"";
	const ESCAPED_QUOTE = "\"\"";
	const CHARACTERS_THAT_MUST_BE_QUOTED = '/[,"\n]/';

/**
 *
 * escapes a given string for csv file
 * @param string $s
 * @return string
 **/
	public static function escape( $s ) {
		if ( strpos($s, self::QUOTE) !==false) {
			$s = str_replace(self::QUOTE, self::ESCAPED_QUOTE, $s);
		}

		if ( preg_match(self::CHARACTERS_THAT_MUST_BE_QUOTED, $s))
			$s = self::QUOTE . $s . self::QUOTE;

		return $s;
	}

	/**
 *
 * unescapes a given string from csv file
 * @param string $s
 * @return string
 **/
	public static function unescape( $s ) {

		require_once('StringLib.php');
		if (StringLib::startsWith($s, self::QUOTE ) &&
		StringLib::endsWith($s, self::QUOTE ) ) {

			$s = substr($s, 1, strlen($s) - 2);
			if ( strpos($s, self::ESCAPED_QUOTE) !==false) {
				$s = str_replace(self::ESCAPED_QUOTE, self::QUOTE, $s);
			}

		}

		return $s;
	}

}