<?php
/**
 * String Utility Library
 *
 * This Utility Library is for string manipulation.
 * http://github.com/simkimsia/StringUtil
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
}
?>