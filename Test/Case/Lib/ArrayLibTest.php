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
 * @copyright Copyright 2011-2013, Kim Stacks.
 * @link http://stacktogether.com
 * @author Kim Stacks <kim@stacktogether.com>
 * @package UtilityLib
 * @subpackage UtilityLib.Test.Case.Lib
 * @filesource
 * @version 0.4
 * @lastmodified 2013-09-28
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
 */
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

/**
 * 
 * test function extractToNest 
 *
 * @return void
 */
	public function testExtractToNest() {
		// 3 tests:
		//   1. model.n to n.model
		//   2. n.model to model.n
		//   3. n.model to model.n

		$data = array(
			'Article' => array(
				array('id' => 1, 'title' => 'ABC', 'body' => 'Long Story'),
				array('id' => 2, 'title' => 'DEF', 'body' => 'I wrote this Song'),
				array('id' => 3, 'title' => 'GHI', 'body' => 'My Way'),
			),
			'Comments' => array(
				array('id' => 1, 'body' => 'I love this!!', 'article_id' => 1),
				array('id' => 2, 'body' => 'I hate this!!', 'article_id' => 1),
				array('id' => 3, 'body' => 'I have nothing to say', 'article_id' => 2),
			)
		);

		// expected
		$expected = array(
			array(
				'Article' => array(
					'id' => 1, 'title' => 'ABC', 'body' => 'Long Story'
				)
			),
			array(
				'Article' => array(
					'id' => 2, 'title' => 'DEF', 'body' => 'I wrote this Song'
				),
			),
				'Article' => array(
					'id' => 3, 'title' => 'GHI', 'body' => 'My Way'
				),
			),
		);
		// test 1. model.n to n.model
		$options	= array('from' => 'Article.{n}', 'to' => '{n}.Article');
		$output		= ArrayLib::extractToNest($data, $options);
		$this->assertEquals($output, $expected);

		$data = array(
			'Article' => array(
				'id' => 1, 'title' => 'ABC', 'body' => 'Long Story'
			),
			'Article' => array(
				'id' => 2, 'title' => 'DEF', 'body' => 'I wrote this Song'
			),
			'Article' => array(
				'id' => 3, 'title' => 'GHI', 'body' => 'My Way'
			),
		);

		$expected = array(
			'Article' => array(
				array('id' => 1, 'title' => 'ABC', 'body' => 'Long Story'),
				array('id' => 2, 'title' => 'DEF', 'body' => 'I wrote this Song'),
				array('id' => 3, 'title' => 'GHI', 'body' => 'My Way'),
			)
		);

		// test 2. n.model to model.n
		$options	= array('from' => '{n}.Article', 'to' => 'Article.{n}');
		$output		= ArrayLib::extractToNest($data, $options);
		$this->assertEquals($output, $expected);

		$data = array(
			'Article' => array(
				'id' => 1, 'title' => 'ABC', 'body' => 'Long Story'
			),
			'Article' => array(
				'id' => 2, 'title' => 'DEF', 'body' => 'I wrote this Song'
			),
			'Article' => array(
				'id' => 3, 'title' => 'GHI', 'body' => 'My Way'
			),
		);

		$expected = array(
			'Post' => array(
				'id' => 1, 'title' => 'ABC', 'body' => 'Long Story'
			),
			'Post' => array(
				'id' => 2, 'title' => 'DEF', 'body' => 'I wrote this Song'
			),
			'Post' => array(
				'id' => 3, 'title' => 'GHI', 'body' => 'My Way'
			),
		);

		// test 3. n.model1 to n.model2
		$options	= array('from' => '{n}.Article', 'to' => '{n}.Post');
		$output		= ArrayLib::extractToNest($data, $options);
		$this->assertEquals($output, $expected);
	}
}
?>