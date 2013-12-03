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
 * @copyright Copyright 2011-2013, Kim Stacks.
 * @link http://stacktogether.com
 * @author Kim Stacks <kim@stacktogether.com>
 * @package UtilityLib
 * @subpackage UtilityLib.Lib
 * @version 0.3
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
 * Take in html content as string and find all the <script src="yada.js" ... >
 * and add $prepend to the src values except when there is http: or https:
 *
 * @param $html String The html content
 * @param $prepend String The prepend we expect in front of all the href in css tags
 * @param $tidy Boolean Optional. Default true. Indicate whether to return clean HTML
 * @return String The new $html content after find and replace.
 *
 */
	public static function prependHrefForCssTags($html, $prepend, $tidy = true) {
		$html = self::_prependAttrForTags($html, $prepend, 'css', $tidy);
		return $html;
	}

/**
 *
 * Take in html content as string and find all the <script src="yada.js" ... >
 * and add $prepend to the src values except when there is http: or https:
 *
 * @param $html String The html content
 * @param $prepend String The prepend we expect in front of all the src in script tags
 * @param $tidy Boolean Optional. Default true. Indicate whether to return clean HTML
 * @return String The new $html content after find and replace.
 *
 */
	public static function prependSrcForJsTags($html, $prepend, $tidy = true) {
		$html = self::_prependAttrForTags($html, $prepend, 'js', $tidy);
		return $html;
	}

/**
 *
 * Take in html content as string and find all the <img src="yada.png" ... >
 * and add $prepend to the src values except when there is http: or https:
 *
 * @param $html String The html content
 * @param $prepend String The prepend we expect in front of all the src in img tags
 * @param $tidy Boolean Optional. Default true. Indicate whether to return clean HTML
 * @return String The new $html content after find and replace.
 *
 */
	public static function prependSrcForImgTags($html, $prepend, $tidy = true) {
		$html = self::_prependAttrForTags($html, $prepend, 'img', $tidy);
		return $html;
	}

/**
 *
 * Take in html content assuming it is full page and clean it up with indentation
 * Assumed to use Tidy
 *
 * @param $html String The html content
 * @return String Cleaned up $html content.
 *
 */
	public static function cleanHTMLContent($html) {
		// Specify configuration
		$config = array(
		'show-body-only' => false,
		'clean' => true,
		'char-encoding' => 'utf8',
		'add-xml-decl' => false,
		'add-xml-space' => false,
		'output-html' => false,
		'output-xml' => false,
		'output-xhtml' => true,
		'numeric-entities' => false,
		'ascii-chars' => false,
		'doctype' => 'transitional',
		'bare' => true,
		'fix-uri' => true,
		'indent' => true,
		'indent-spaces' => 4,
		'tab-size' => 4,
		'wrap-attributes' => true,
		'wrap' => 0,
		'indent-attributes' => true,
		'join-classes' => false,
		'join-styles' => false,
		'enclose-block-text' => true,
		'fix-bad-comments' => true,
		'fix-backslash' => true,
		'replace-color' => false,
		'wrap-asp' => false,
		'wrap-jste' => false,
		'wrap-php' => false,
		'write-back' => true,
		'drop-proprietary-attributes' => false,
		'hide-comments' => false,
		'hide-endtags' => false,
		'literal-attributes' => false,
		'drop-empty-paras' => true,
		'enclose-text' => true,
		'quote-ampersand' => true,
		'quote-marks' => false,
		'quote-nbsp' => true,
		'vertical-space' => true,
		'wrap-script-literals' => false,
		'tidy-mark' => false,
		'merge-divs' => false,
		'repeated-attributes' => 'keep-last',
		'break-before-br' => true,
		);

		// Tidy
		$tidy = new Tidy();
		$html = $tidy->repairString($html, $config, 'utf8');
		return $html;
	}

/**
 *
 * Take in html content as string and find all the img, js and css tags
 * and add $prepend to the href/src values except when there is http: or https:
 *
 * @param $html String The html content
 * @param $prepend String The prepend we expect in front of all the href/src in img, js and css tags
 * @param $tidy Boolean Optional. Default true. Indicate whether to return clean HTML
 * @return String The new $html content after find and replace.
 *
 */
	public static function prependAttrForAllTags($html, $prepend, $tidy = true) {
		$html = self::prependHrefForCssTags($html, $prepend, false);
		$html = self::prependSrcForJsTags($html, $prepend, false);
		$html = self::prependSrcForImgTags($html, $prepend, false);

		if ($tidy) {
			$html = self::cleanHTMLContent($html);
		}

		return $html;
	}

/**
 *
 * Using PHP's native DOM to solve this.
 *
 * Take in html content as string and find all the <script src="yada.js" ... >
 * and add $prepend to the src values except when there is http: or https:
 *
 * @param $html String The html content
 * @param $prepend String The prepend we expect in front of all the href in css tags
 * @param $tag String. Acceptable values are img, js, css.
 * @param $tidy Boolean Optional. Default true. Indicate whether to return clean HTML
 * @return String The new $html content after find and replace. This is dirty html because we use DOM
 *
 */
	protected static function _prependAttrForTags($html, $prepend, $tag, $tidy = true) {
		if ($tag == 'css') {
			$element = 'link';
			$attr = 'href';
		} else if ($tag == 'js') {
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

		$dom_document = new DOMDocument();

		$dom_document->loadHTML($html);
		$elements = $dom_document->getElementsByTagName($element);
		foreach($elements as $singleElement){
			$attrValue = $singleElement->getAttribute($attr);
			$singleElement->setAttribute( $attr , $prepend.$attrValue );
		}
		$html = $dom_document->saveHTML();

		if ($tidy) {
			$html = self::cleanHTMLContent($html);
		}

		return $html;
	}

	public static function replaceCssLinksWithCakeHelper($html, $tidy = true) {
		$domDoc = new DOMDocument();

		$html = self::cleanHTMLContent($html);

		$domDoc->loadHTML($html);
		$elements = $domDoc->getElementsByTagName('link');
		$format = '<?php echo $this->Html->css("%1$s"); ?>';
		foreach($elements as $singleElement){
			$attrValue = $singleElement->getAttribute('href');
			$echo = sprintf($format, $attrValue);
			debug($echo);
			$frag = $domDoc->createDocumentFragment();
			$frag->appendXML($echo);
			$singleElement->parentNode->replaceChild($frag, $singleElement);
			//$singleElement->parentNode->replaceChild($echo, $singleElement);
		}
		$html = $domDoc->saveHTML();

		if ($tidy) {
			$html = self::cleanHTMLContent($html);
		}

		return $html;
	}

/**
 *
 * Replace newline
 *
 * Replace newlines with something else
 *
 * @param $subject String The subject we are searching for newlines and replace
 * @param $replace String The replacement for the newlines
 * @return String The new subject with the newlines replaced
 */
	public static function replaceNewLines($subject, $replace) {
		return str_replace(array("\r\n", "\n\r", "\n", "\r"), $replace, $subject);
	}

/**
 *
 * Count occurences of newlines in a long string
 * taken from http://stackoverflow.com/a/7955446/80353
 *
 * @param $subject String The subject we are searching for newlines
 * @return int
 */
	public static function countNewLines($subject) {
		$lines_arr = preg_split('/\r\n|\n\r|\n|\r/',$subject);
		$num_newlines = count($lines_arr);
		return $num_newlines;
	}

/**
 *
 * Explode string using newline as delimiter.
 * Note that empty lines are also removed
 *
 * @param $subject String The subject we are going to explode with
 * @return Array The array of lines
 */
	public static function explodeByNewLines($subject) {
		return preg_split('/\n|\r/', $subject, -1, PREG_SPLIT_NO_EMPTY);
	}

}
?>