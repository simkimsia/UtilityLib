<?php
/**
 * Time Utility Library Test Case
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
App::uses('TimeLib', 'UtilityLib.Lib');

class TimeLibTestCase extends CakeTestCase {

	public function tearDown() {
		ClassRegistry::flush();
		parent::tearDown();
	}

/**
 *
 * test function translate
 *
 * @return void
 */
	public function testTranslate() {
		$input		= '2013-09-26 22:52:49';
		$expected	= '2013-09-26T22:52Z';
		$options = array(
			'to' => TimeLib::MYSQL_DATETIME,
			'from' => TimeLib::ISO_8601_DATETIME
		);
		$output = TimeLib::translate($input, $options);
		$this->assertEquals($expected, $output);
	}

}