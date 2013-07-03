<?php
/**
 *
 * Utility Library is the utility belt of useful functions
 * http://github.com/simkimsia/UtilityLib/
 *
 * String Utility Library
 *
 * This Utility Library is for string manipulation methods
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
 * @lastmodified 2013-03-12 
 */
class StringLib {

/**
* 
* Checks input string does NOT have single quotes at the beginning and at the end.
* Wraps it in single quotes.
* E.g., $input is string. Returns 'string'
*
* @param string $input. Input string.
* @return string. If string has either a single quote at front or end, it is returned unchanged.
**/
	public static function wrapStringInQuotes($input) {
		$noStartsWithQuote = !self::startsWith($input, "'");
		$noEndsWithQuote   = !self::endsWith($input, "'");

		if ($noStartsWithQuote AND $noEndsWithQuote) {
				return "'" . $input . "'";
		}
		return $input;
	}

/**
* 
* Takes input array and wraps every value-string in single quotes where applicable.a
*
* @param array $array. Input array.
* @param boolean $recursive. Set true if you want the wrapping to happen at all levels of array. Default false.
* @return array. 
**/
	public static function iterateArrayWrapStringValuesInQuotes($array, $recursive = false) {
		foreach($array as $key=>$value) {
			if (is_array($value) && $recursive) {
				$array[$key] = self::iterateArrayWrapStringValuesInQuotes($value);
			} elseif (is_string($value) OR is_numeric($value)) {
				$array[$key] = self::wrapStringInQuotes($value);
			}
		}
		return $array;
	}

/**
* 
* Looks inside a string and checks if it STARTS with a substring. Works for case-sensitive and case-insensitive
*
* @param string $haystack. The string to be searched.
* @param string $needle. The substring we are searching at the front
* @param boolean $case. Set to true if case-sensitive search is required
* @return boolean. Returns true if substring is at beginning of string
*
**/
	public static function startsWith($haystack,$needle,$case=true) {
		if($case){return (strcmp(substr($haystack, 0, strlen($needle)),$needle)===0);}
		return (strcasecmp(substr($haystack, 0, strlen($needle)),$needle)===0);
	}

/**
* 
* Looks inside a string and checks if it ENDS with a substring. Works for case-sensitive and case-insensitive
*
* @param string $haystack. The string to be searched.
* @param string $needle. The substring we are searching at the end.
* @param boolean $case. Set to true if case-sensitive search is required
* @return boolean. Returns true if substring is at beginning of string
*
**/
	public static function endsWith($haystack,$needle,$case=true) {
		if($case){return (strcmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);}
		return (strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);
	}

/**
 * Random string generator function
 *
 * This function will randomly generate a password from a given set of characters
 *
 * @param int = 8, length of the password you want to generate
 * @param string = 0123456789abcdefghijklmnopqrstuvwxyz all possible values
 * @return string, the password
 */
	public static function generateRandom ($length = 8, $options = array()) {
		// initialize variables
		$password 	= "";
		$i 			= 0;
		$possible 	= '';

		$numerals = '0123456789';
		$lowerAlphabet = 'a$bcdefghijklmnopqrstuvwxyz';
		$upperAlphabet = strtoupper($lowerAlphabet);

		$defaultOptions = array('type'=>'alphanumeric', 'case'=>'mixed');

		$options = array_merge($defaultOptions, $options);

		if ($options['type'] == 'alphanumeric') {
			$possible = $numerals;
			if ($options['case'] == 'lower' OR $options['case'] == 'mixed') {
				$possible .= $lowerAlphabet;
			} elseif ($options['case'] == 'upper' OR $options['case'] == 'mixed') {
				$possible .= $upperAlphabet;
			}
		}

		// add random characters to $password until $length is reached
		while ($i < $length) {
			// pick a random character from the possible ones
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

			// we don't want this character if it's already in the password
			if (!strstr($password, $char)) { 
				$password .= $char;
				$i++;
			}
		}
		return $password;
	}

/**
* generate the string in the email format containing "name" <hello@example.com>
*
* input is array with the keys as addresses and the names are values
**/
	public static function emailAddressFormat($namesAndAddresses = array()) {
		if (empty($namesAndAddresses)) {
			return '';
		}
		$result = '';
		App::uses('Validation', 'Utility');
		foreach($namesAndAddresses as $email => $name) {
			if (Validation::email($email) && is_string($name)) {
				$result .= '"'.$name.'" <' . $email . '>';
				$result .= ', ';
			} else if (Validation::email($name)) {
				$result .= '<' . $name . '>';
				$result .= ', ';
			}
		}
		return rtrim($result, ', ');
	}

/**
*
* returns the string back into email array
*
* expect "name" <email@example.com> separated by , or ;
*
**/
	public static function returnEmailArray($emailString) {
		App::uses('Validation', 'Utility');
		
		$result = array();
		
		$emailAddresses = preg_split("/[,;]+/", $emailString);
		
		foreach($emailAddresses as $emailAddress) {
			$emailElements = preg_split("/[<>]+/", $emailAddress);
			$email = '';
			$name = '';

			foreach($emailElements as $e) {
				if (!empty($e)) {
					if (Validation::email($e)) {
						$email = $e;
					} else if (is_string($e)) {
						$e = str_replace('"', '', $e);
						$name = rtrim($e, ' ');
					}

				}
			}
			
			if (!empty($email)) {
				$result[$email] = $name;
			}
		}
		
		return $result;
	}

/**
 *
 * Take in html content as string and find all the <link href="yada.css" ... >
 * and add $prepend to the href values except when there is http: or https:
 *
 * @param $html String The html content
 * @param $prepend String The prepend we expect in front of all the href in css tags
 * @return String The new $html content after find and replace. 
 * 
 */
	public static function prependHrefForCssTags($html, $prepend) {
		return preg_replace('/(<link\b.+href=")(?!http)([^"]*)(".*>)/', '$1'.$prepend.'$2$3$4', $html);
	}

/**
 *
 * Take in html content as string and find all the <script src="yada.js" ... >
 * and add $prepend to the src values except when there is http: or https:
 *
 * @param $html String The html content
 * @param $prepend String The prepend we expect in front of all the href in css tags
 * @return String The new $html content after find and replace. 
 * 
 */
	public static function prependHrefForCssTags($html, $prepend) {
		return self::_prependAttrForTags($html, $prepend, 'css');
	}

/**
 *
 * Take in html content as string and find all the <script src="yada.js" ... >
 * and add $prepend to the src values except when there is http: or https:
 *
 * @param $html String The html content
 * @param $prepend String The prepend we expect in front of all the src in script tags
 * @return String The new $html content after find and replace. 
 * 
 */
	public static function prependSrcForJsTags($html, $prepend) {
		return self::_prependAttrForTags($html, $prepend, 'js');
	}

/**
 *
 * Take in html content as string and find all the <img src="yada.png" ... >
 * and add $prepend to the src values except when there is http: or https:
 *
 * @param $html String The html content
 * @param $prepend String The prepend we expect in front of all the src in img tags
 * @return String The new $html content after find and replace. 
 * 
 */
	public static function prependSrcForImgTags($html, $prepend) {
		return self::_prependAttrForTags($html, $prepend, 'img');
	}

/**
 *
 * Take in html content as string and find all the img, js and css tags
 * and add $prepend to the href/src values except when there is http: or https:
 *
 * @param $html String The html content
 * @param $prepend String The prepend we expect in front of all the href/src in img, js and css tags
 * @return String The new $html content after find and replace. 
 * 
 */
	public static function prependAttrForAllTags($html, $prepend) {
		$html = self::prependHrefForCssTags($html, $prepend);
		$html = self::prependSrcForJsTags($html, $prepend);
		$html = self::prependSrcForImgTags($html, $prepend);
		return $html;
	}

/**
 *
 * Take in html content as string and find all the <script src="yada.js" ... >
 * and add $prepend to the src values except when there is http: or https:
 *
 * @param $html String The html content
 * @param $prepend String The prepend we expect in front of all the href in css tags
 * @return String The new $html content after find and replace. 
 * 
 */
	protected static function _prependAttrForTags($html, $prepend, $tag) {
		if ($tag == 'css') {
			$element = 'link';
			$attr = 'href';
		}
		else if ($tag == 'js') {
			$element = 'script';
			$attr = 'src';
		}
		else if ($tag == 'img') {
			$element = 'img';
			$attr = 'src';
		}
		else {
			// wrong tag so return unchanged
			return $html;
		}
		return preg_replace('/(<'.$element.'\b.+'.$attr.'=")(?!http)([^"]*)(".*>)/', '$1'.$prepend.'$2$3$4', $html);
	}
}
?>