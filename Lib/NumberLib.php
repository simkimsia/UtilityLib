<?php
/**
 * Number Utility Library
 *
 * This Utility Library allows Number Helper methods to be used as Library static functions
 * http://github.com/simkimsia/NumberLib
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
App::uses('View', 'View');
App::uses('NumberHelper', 'View/Helper');
App::uses('Controller', 'Controller');

class NumberLib {
	
	/**
	*
	* Displays a number with the specified amount of precision (decimal places). Will round in order to maintain the level of precision defined. 
	* 
	* @param float $number The value to covert
	* @param integer $precision The number of decimal places to display
	* @return float Return a newly formatted number
	**/
	public static function precision($number, $precision = 3) {
		$numberHelper = new NumberHelper(new View(new Controller()));
		return $numberHelper->precision($number, $precision);
	}
	
	/**
	*
	* display a number in common currency formats (EUR,GBP,USD)
	* 
	* @param float $number The value to covert.
	* @param string $currency The known currency format to use.
	* @param array $options Options, see below.
	* Option Description
	*	before - The currency symbol to place before whole numbers ie. ‘$’
	*	after -	The currency symbol to place after decimal numbers ie. ‘c’. Set to boolean false to use no decimal symbol. eg. 0.35 => $0.35.
	*	zero - The text to use for zero values, can be a string or a number. ie. 0, ‘Free!’
	*	places - Number of decimal places to use. ie. 2
	*	thousands - Thousands separator ie. ‘,’
	*	decimals - Decimal separator symbol ie. ‘.’
	*	negative - Symbol for negative numbers. If equal to ‘()’, the number will be wrapped with ( and )
	*	escape - Should the output be htmlentity escaped? Defaults to true
	*
	* If a non-recognized $currency value is supplied, it is prepended to a USD formatted number. 
	**/
	public static function currency($number, $currency= 'USD', $options = array()) {
		$numberHelper = new NumberHelper(new View(new Controller()));
		return $numberHelper->currency($number, $currency, $options);		
	}
        
}
?>