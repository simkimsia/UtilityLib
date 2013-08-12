<?php
/**
 *
 * Utility Library is the utility belt of useful functions
 * http://github.com/simkimsia/UtilityLib/
 *
 * Zip Utility Library
 *
 * This Utility Library is for zip file manipulation methods
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
 * @version 0.2
 * @lastmodified 2013-03-13
 */
class ZipLib {

/**
 * 
 * Given a path of a folder, zip folder recursively to produce a zip file.
 * Automatically ignores ".", "..", and ".DS_Store". 
 *
 * #### Options
 *
 * - destination: string Path to the final output zip file. Default will create the zip file adjacent to folder.
 * - include_dir: boolean If true (default), will include the folder defined in $folderPath.
 * - ignore_files: array Array of files to be ignored. Need to state the filename in its entirety to work.
 * Any files stated here will be added on top of the automatically ignored files.
 *
 * @param string $folderPath Path to the folder to be zipped up
 * @param array $options Options to use when zipping a folder, see $options above.
 * @return boolean Return true if successful
 */
	public static function zipFolder($folderPath, $options = array()) {
		if (!extension_loaded('zip') || !file_exists($folderPath)) {
			return false; // should throw exception in future
		}

		$source				= str_replace('\\', '/', realpath($folderPath));
		$sourceDir			= dirname($source);
		$defaultDestination		= $sourceDir . '/' . basename($folderPath) . '.zip';
		$automaticallyIgnoredFiles	= array('.', '..', '.DS_Store');

		$defaultOptions = array(
			'destination'	=> $defaultDestination,
			'include_dir'	=> true,
			'ignore_files'	=> array()
		);

		$options = array_merge($defaultOptions, $options);

		$ignoreFiles = array_merge($automaticallyIgnoredFiles, $options['ignore_files']);

		$destination = $options['destination'];
		$include_dir = $options['include_dir'];

		if (file_exists($destination)) {
			unlink ($destination);
		}

		$zip = new ZipArchive();
		if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
			return false;
		}

		if (is_dir($source) === true) {
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

			if ($include_dir) {
				$arr = explode("/",$source);
				$maindir = $arr[count($arr)- 1];
				$source = "";
				for ($i=0; $i < count($arr) - 1; $i++) { 
					$source .= '/' . $arr[$i];
				}
				$source = substr($source, 1);
				$zip->addEmptyDir($maindir);
			}

			foreach ($files as $file) {
				$file = str_replace('\\', '/', $file);

				// purposely ignore files that are irrelevant
				if( in_array(substr($file, strrpos($file, '/')+1), $ignoreFiles) )
					continue;

				$file = realpath($file);

				if (is_dir($file) === true)
				{
					$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
				}
				else if (is_file($file) === true)
				{
					$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
				}
			}
		} else if (is_file($source) === true) {
			$zip->addFromString(basename($source), file_get_contents($source));
		}

		return $zip->close();
	}
}