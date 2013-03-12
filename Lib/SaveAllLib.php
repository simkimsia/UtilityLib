<?php
/**
 * 
 * Utility Library is the utility belt of useful functions
 * http://github.com/simkimsia/UtilityLib/
 *
 * SaveAll Utility Library
 *
 * This Utility Library is for SaveAll methods
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
 * @lastmodified 2011-12-02 
 */
class SaveAllLib {
	
	/**
	* 
	* Returns true if a saveAll non-atomic transaction is successful. Returns false if otherwise.
	*
	* @param array $result
	* @return array
	**/
	public static function hasASuccessful($result) {
		
		if (is_array($result)) {
			foreach($result as $key=>$value) {
				if ($value === false) {
					return false;
				}
			}
		   	return true;
		} elseif (is_boolean($result)) {
			return $result;
		}
		
		return false;
	}
}
?>