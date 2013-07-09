<?php
/**
 * Array Utility Library Test Case
 *
 * This Utility Library is for string manipulation.
 * http://github.com/simkimsia/ArrayUtil
 * 
 * Test case written for Cakephp 2.0
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2013, Sim Kim Sia
 * @link http://simkimsia.com
 * @author Sim Kim Sia (kimcity@gmail.com)
 * @package app
 * @subpackage app.Test.Case.Lib
 * @filesource
 * @version 0.3
 * @lastmodified 2013-07-09
 */
App::uses('ArrayLib', 'UtilityLib.Lib');

class ArrayLibTestCase extends CakeTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {
		ClassRegistry::flush();
		parent::tearDown();
	}
	
	/**
	* 
	* test function deepKSort 
	*
	* @return void
	**/
	public function testDeepKSort() {
		// 3 layers of array
		$inputArray = array(
			'a' => array('ap' => array('apply', 'apple'), 'am' => 'amp'),
			'b' => array('b'),
			'c' => array(
				'ca' => array(
					'cap' => array('captain', 'capsize'), 
					'cam' => array('camera')
				), 
				'ce' => array(
					'cel' => array('cellar', 'celery')
				 )
			)
		);

		// expected
		$expected = array(
			'a' => array('am' => 'amp', 'ap' => array('apply', 'apple')),
			'b' => array('b'),
			'c' => array(
				'ca' => array(
					'cam' => array('camera'),
					'cap' => array('captain', 'capsize')
				), 
				'ce' => array(
					'cel' => array('cellar', 'celery')
				 )
			)
		);
		ArrayLib::deepKSort($inputArray);
		$this->assertEquals($inputArray, $expected);
	}
}
?>