<?php
/**
 * String Utility Library Test Case
 *
 * http://github.com/simkimsia/UtilityLib
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
 * @lastmodified 2013-09-28
 */
App::uses('StringLib', 'UtilityLib.Lib');

class StringLibTestCase extends CakeTestCase {

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
		$input = 'happy';
		$expected = '\'happy\'';
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
		$input = array('happy', array('whoopie!!', 'hahaha'));
		$expected = array('\'happy\'', array('whoopie!!', 'hahaha'));
		$this->assertEquals(StringLib::iterateArrayWrapStringValuesInQuotes($input, false), $expected);

		// array with 1 string and 1 inner array. got recursion
		$input = array('happy', array('whoopie!!', 'hahaha'));
		$expected = array('\'happy\'', array('\'whoopie!!\'', '\'hahaha\''));
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
 */
	public function testEndsWith() {
		$this->assertTrue(StringLib::endsWith('hello kitty', 'tty', true));

		$this->assertTrue(StringLib::endsWith('hello kitty', 'tty', false));

		$this->assertFalse(StringLib::endsWith('hello kitty', 'tTy', true));

		$this->assertTrue(StringLib::endsWith('hello kitty', 'tTy', false));

		$this->assertFalse(StringLib::endsWith('hello kitty', 'nope', true));

		$this->assertFalse(StringLib::endsWith('hello kitty', 'nope', false));
	}

/**
 *
 * test function replaceNewLines
 *
 * @return void
 */
	public function testReplaceNewLines() {
		// GIVEN the following $subject and $replace
		$subject = "This is line 1\r\nThis is line 2\n\rThis is line 3\rThis is line 4\n";
		$replace = "<br />";

		// WHEN we run the replaceNewLines
		$result = StringLib::replaceNewLines($subject, $replace);

		// THEN we expect the following
		$expected = "This is line 1<br />This is line 2<br />This is line 3<br />This is line 4<br />";
		$this->assertEquals($result, $expected);
	}

/**
 *
 * test function countNewLines
 *
 * @return void
 */
	public function testCountNewLines() {
		// GIVEN the following $subject
		$subject = "This is line 1\r\nThis is line 2\n\rThis is line 3\rThis is line 4\n";

		// WHEN we run the countNewLines
		$result = StringLib::countNewLines($subject);

		// THEN we expect 5 lines.
		// WHY? because we count the line after the return carriage at end of line 4 as another line
		$expected = 5;
		$this->assertEquals($result, $expected);
	}

/**
 *
 * test function explodeByNewLines
 *
 * @return void
 */
	public function testExplodeByNewLines() {
		// GIVEN the following $subject
		$subject = "This is line 1\r\n\r\n\r\nThis is line 2\n\rThis is line 3\rThis is line 4\n";

		// WHEN we run the explodeByNewLines
		$result = StringLib::explodeByNewLines($subject);

		// THEN we expect 4 lines.
		// WHY? because empty lines are removed
		$expected = 4;
		$this->assertEquals($expected, count($result));

		// AND the result is as follows
		$expected = array(
			"This is line 1",
			"This is line 2",
			"This is line 3",
			"This is line 4"
		);
		$this->assertEquals($expected, $result);
	}

/**
 *
 * test function substrToLastWord
 *
 * @return void
 */
	public function testSubstrToLastWord() {
		// GIVEN the following $subject
		$subject = "Word1 Word2 ~!@#$%^&*()_+=-`1234567890";

		// WHEN we run the substrToLastWord
		$result = StringLib::substrToLastWord($subject, 14);

		// THEN we expect Word1 Word2
		// WHY? because character limit at position 13 is ! but it cuts
		// off the word
		$expected = "Word1 Word2";
		$this->assertEquals($expected, $result);
	}

}