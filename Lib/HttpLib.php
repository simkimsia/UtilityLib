<?php
/**
 * Utility Library is the utility belt of useful functions
 * http://github.com/simkimsia/UtilityLib/
 *
 * Http Utility Library
 *
 * This Utility Library allows get ip address by host
 *
 * Copyright 2013, Kim Stacks
 * Singapore
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2011-2013, Kim Stacks.
 * @link http://stacktogether.com
 * @author Kim Stacks <kim@stacktogether.com>
 * @package UtilityLib
 * @subpackage UtilityLib.Lib
 * @filesource
 * @version 0.1
 * @lastmodified 2013-09-28
 */

class HttpLib {

/**
 *
 * Returns IP address based on domain host.
 *
 * @param string $host The domain. Eg, www.abc.com
 * @param integer $timeout The number of seconds before timeout
 * @return string Returns IP address
 */
	public static function getAddrByHost($host, $timeout = 3) {
		$host	= str_replace('http://', '', $host);
		$query	= `nslookup -timeout=$timeout -retry=1 $host`;
		if (preg_match('/\nAddress: (.*)\n/', $query, $matches)) {
			return trim($matches[1]);
		}
		return $host;
	}
}