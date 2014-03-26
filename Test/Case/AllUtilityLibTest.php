<?php
/**
 * AllUtilityLibTest test suite file
 *
 * will run ALL the tests for this CakePHP Plugin
 *
 * Copyright 2013, Kim Stacks
 * Singapore
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2013, Kim Stacks.
 * @link http://stacktogether.com
 * @author Kim Stacks <kim@stacktogether.com>
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @package UtilityBehaviors
 * @subpackage UtilityBehaviors.Model.Behavior
 * @filesource
 * @version 0.1
 * @lastmodified 2013-11-07
 */
class AllUtilityLibTest extends PHPUnit_Framework_TestSuite {

/**
 * suite method, defines tests for this suite.
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All PLUGIN_NAME test');
		$suite->addTestDirectoryRecursive(App::pluginPath('UtilityLib') . 'Test' . DS . 'Case' . DS);

		return $suite;
	}
}