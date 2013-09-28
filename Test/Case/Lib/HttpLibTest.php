<?php
/**
 * Http Utility Library Test Case
 *
 * This Utility Library is for http manipulation.
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
App::uses('HttpLib', 'UtilityLib.Lib');

class HttpLibTestCase extends CakeTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {
		ClassRegistry::flush();
		parent::tearDown();
	}

/**
 * 
 * test function getAddrByHost 
 *
 * @return void
 */
	public function testGetAddrByHost() {
		// test using google.com
		$host = 'google.com';
		$regExp = '/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/';

		$receivedIP = HttpLib::getAddrByHost($host);
		// preg_match returns 0 or 1 time the number of times match occurs
		// but preg_match will stop the moment there is 1 match
		$this->assertEquals(preg_match($regExp, $receivedIP), 1);
	}

}
?>