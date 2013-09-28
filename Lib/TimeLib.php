<?php
/**
 * Utility Library is the utility belt of useful functions
 * http://github.com/simkimsia/UtilityLib/
 *
 * Time Utility Library
 *
 * This Utility Library is for manipulating strings and objects to do with date, time, and timezones
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
 * @package UtilityLib
 * @subpackage UtilityLib.Lib
 * @filesource
 * @version 0.1
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @lastmodified 2013-09-28
 */

class TimeLib {

	const MYSQL_DATETIME		= 'MYSQL_DATETIME';
	const ISO_8601_DATE		= 'ISO_8601_DATE';
	const ISO_8601_DATETIME	= 'ISO_8601_DATETIME';
/**
 * check whether right now is still not later than a given MySQL datetime value by a specific time interval
 * e.g. nowStillWithin('2013-09-09 00:00:00', '30 minutes') is asking whether right now is it 
 no later than 2013-09-09 00:30:00 
 * @param $datatimeInMySQL string Expecting yyyy-mm-dd hh:mm:ss format
 * @param $intervalInString string Human-readable date string. E.g. 4 days
 * @return boolean Return true if still within the time period
 */
	public static function nowStillWithin($datetimeInMySQL, $intervalInString = '30 minutes') {
		$mySQLDateTimeObject	= new DateTime($datetimeInMySQL);
		$dateTimeInterval		= date_interval_create_from_date_string($intervalInString);
		$nowDateTimeObject		= new DateTime('now');
		$mySQLDateTimeObject->add($dateTimeInterval);
		$stillInTime = ($mySQLDateTimeObject > $nowDateTimeObject);
		return $stillInTime;
	}

/**
 * find out what a specific time ago was in a specific format.
 * currently works for 3 kinds of format: MySQL datetime string aka yyyy-mm-dd hh:mm:ss,
 * the ISO-8601 date format aka yyyy-mm-dd, and ISO-8601 datetime format yyyy-mm-ddThh:mmZ
 * Read more about ISO-8601 at http://en.wikipedia.org/wiki/ISO_8601
 * @param $agoInString String Human readable string indicating how far back you are interested in
 * @param $format String Currently accepts 3 possible values `MYSQL_DATETIME`, `ISO_8601_DATE`, `ISO_8601_DATETIME`
 */
	public static function getTimeAgo($agoInString = '30 minutes', $format = 'MYSQL_DATETIME') {
		$nowDateTimeObject = new DateTime('now');
		$agoInterval = date_interval_create_from_date_string($agoInString);
		$timeAgoDateTimeObject = $nowDateTimeObject->sub($agoInterval);
		if ($format == 'MYSQL_DATETIME') {
			return $timeAgoDateTimeObject->format('Y-m-d H:i:s');
		} else {
			return $timeAgoDateTimeObject->format('Y-m-d H:i:s');
		}
	}

/**
 * translate from 1 of the 3 available formats to the other
 *
 * @param $value String. E.g., 3133-01-07 17:49:52
 * @param $options Array Contains the following keys:
   - `from` String Required 1 of the 3 possible formats
   - `to`	String Required 1 of the 3 possible formats
 * @return String the new string of the desired format indicated in $options['to']
 */
	public static function translate($value, $options = array()) {
		// currently only works from MYSQL_DATETIME to ISO_8601_DATETIME
		$date = substr($value, 0, 10); // we should get 3133-01-07
		$time = substr($value, 11, 5); // we should get back 17:49
		return "$dateT$timeZ";
	}

}