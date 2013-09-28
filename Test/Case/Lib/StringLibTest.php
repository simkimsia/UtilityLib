<?php
/**
 * String Utility Library Test Case
 *
 * This Utility Library is for string manipulation.
 * http://github.com/simkimsia/StringUtil
 * 
 * Test case written for Cakephp 2.0
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2011-2013, Kim Stacks.
 * @link http://stacktogether.com
 * @author Kim Stacks <kim@stacktogether.com>
 * @package UtilityLib
 * @subpackage UtilityLib.Test.Case.Lib
 * @filesource
 * @version 0.1
 * @lastmodified 2011-10-03 
 */
App::uses('StringLib', 'UtilityLib.Lib');

class StringLibTestCase extends CakeTestCase {

	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {
		ClassRegistry::flush();
		parent::tearDown();
	}

/**
 * 
 * test function wrapStringInQuotes 
 *
 * @return void
 */
	public function testWrapStringInQuotes() {
		// starts with quotes. expect no change
		$input = '\'happy';
		$this->assertEquals(StringLib::wrapStringInQuotes($input), $input);

		// ends with quotes. expect no change
		$input = 'happy\'';
		$this->assertEquals(StringLib::wrapStringInQuotes($input), $input);

		// ends with quotes. expect no change
		$input 		= 'happy';
		$expected 	= '\'happy\'';
		$this->assertEquals(StringLib::wrapStringInQuotes($input), $expected);		
	}

/**
 * 
 * test function iterateArrayWrapStringValuesInQuotes
 *
 * @return void
 */
	public function testIterateArrayWrapStringValuesInQuotes() {
		// empty array, no change
		$input = array();
		$this->assertEquals(StringLib::iterateArrayWrapStringValuesInQuotes($input), $input);
		
		// array with 1 string and 1 inner array. no recursion
		$input 		= array('happy', array('whoopie!!', 'hahaha'));
		$expected 	= array('\'happy\'', array('whoopie!!', 'hahaha'));
		$this->assertEquals(StringLib::iterateArrayWrapStringValuesInQuotes($input, false), $expected);
		
		// array with 1 string and 1 inner array. got recursion
		$input 		= array('happy', array('whoopie!!', 'hahaha'));
		$expected 	= array('\'happy\'', array('\'whoopie!!\'', '\'hahaha\''));
		$this->assertEquals(StringLib::iterateArrayWrapStringValuesInQuotes($input, true), $expected);
	}
	
	/**
 * 
 * test function startsWith
 *
 * @return void
 **/
	public function testStartsWith() {

		$this->assertTrue(StringLib::startsWith('hello kitty', 'hell', true));
		
		$this->assertTrue(StringLib::startsWith('hello kitty', 'hell', false));
		
		$this->assertFalse(StringLib::startsWith('hello kitty', 'Hell', true));
		
		$this->assertTrue(StringLib::startsWith('hello kitty', 'Hell', false));
		
		$this->assertFalse(StringLib::startsWith('hello kitty', 'nope', true));
		
		$this->assertFalse(StringLib::startsWith('hello kitty', 'nope', false));
	}	
	
	/**
 * 
 * test function endsWith
 *
 * @return void
 **/
	public function testEndsWith() {
		$this->assertTrue(StringLib::endsWith('hello kitty', 'tty', true));
		
		$this->assertTrue(StringLib::endsWith('hello kitty', 'tty', false));
		
		$this->assertFalse(StringLib::endsWith('hello kitty', 'tTy', true));
		
		$this->assertTrue(StringLib::endsWith('hello kitty', 'tTy', false));
		
		$this->assertFalse(StringLib::endsWith('hello kitty', 'nope', true));
		
		$this->assertFalse(StringLib::endsWith('hello kitty', 'nope', false));

	}


}
?>