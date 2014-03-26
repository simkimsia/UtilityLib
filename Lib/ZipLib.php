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

		$source = str_replace('\\', '/', realpath($folderPath));
		$sourceDir = dirname($source);
		$defaultDestination = $sourceDir . '/' . basename($folderPath) . '.zip';
		$automaticallyIgnoredFiles = array('.', '..', '.DS_Store');

		$defaultOptions = array(
			'destination' => $defaultDestination,
			'include_dir' => true,
			'ignore_files' => array()
		);

		$options = array_merge($defaultOptions, $options);

		$ignoreFiles = array_merge($automaticallyIgnoredFiles, $options['ignore_files']);

		$destination = $options['destination'];
		$includeDir = $options['include_dir'];

		if (file_exists($destination)) {
			unlink($destination);
		}

		$zip = new ZipArchive();
		if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
			return false;
		}

		if (is_dir($source) === true) {
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

			if ($includeDir) {
				$arr = explode("/",$source);
				$count = count($arr);
				$maindir = $arr[$count - 1];
				$source = "";

				for ($i = 0; $i < $count - 1; $i++) {
					$source .= '/' . $arr[$i];
				}
				$source = substr($source, 1);
				$zip->addEmptyDir($maindir);
			}

			foreach ($files as $file) {
				$file = str_replace('\\', '/', $file);

				// purposely ignore files that are irrelevant
				if ( in_array(substr($file, strrpos($file, '/') + 1), $ignoreFiles) ) {
					continue;
				}

				$file = realpath($file);

				if (is_dir($file) === true) {
					$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
				} elseif (is_file($file) === true) {
					$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
				}
			}// end foreach
		} elseif (is_file($source) === true) {
			$zip->addFromString(basename($source), file_get_contents($source));
		}

		return $zip->close();
	}

/**
 * Unzip the source_file in the destination dir
 *
 * @param   string      The path to the ZIP-file.
 * @param   string      The path where the zipfile should be unpacked, if false the directory of the zip-file is used
 * @param   boolean     Indicates if the files will be unpacked in a directory with the name of the zip-file (true) or not (false) (only if the destination directory is set to false!)
 * @param   boolean     Overwrite existing files (true) or not (false)
 *
 * @return  boolean     Succesful or not
 */
	public static function unzip($srcFile, $destDir=false, $createZipNameDir=true, $overwrite=true) {
		$zip = zip_open($srcFile);
		if ($zip) {
			$splitter = ($createZipNameDir === true) ? "." : "/";
			if ($destDir === false) {
				$destDir = substr($srcFile, 0, strrpos($srcFile, $splitter)) . "/";
			}
			// Create the directories to the destination dir if they don't already exist
			self::createDirs($destDir);

			$ignoredEntries = array('.DS_Store', '__MACOSX/');

			// For every file in the zip-packet
			while ($zipEntry = zip_read($zip)) {
				// Now we're going to create the directories in the destination directories

				$name = zip_entry_name($zipEntry);
				$ignoreThisEntry = in_array($name, $ignoredEntries);

				if ($ignoreThisEntry) {
					continue;
				}
				// If the file is not in the root dir
				$posLastSlash = strrpos($name, "/");
				if ($posLastSlash !== false) {
					// Create the directory where the zip-entry should be saved (with a "/" at the end)
					self::createDirs($destDir . substr($name, 0, $posLastSlash + 1));
				}

				// Open the entry
				if (zip_entry_open($zip,$zipEntry,"r")) {
					// The name of the file to save on the disk
					$fileUnderscoreName = $destDir . $name;

					// Check if the files should be overwritten or not
					if ($overwrite === true || ($overwrite === false && !file_exists($fileUnderscoreName)) ) {
						// Get the content of the zip entry
						$fstream = zip_entry_read($zipEntry, zip_entry_filesize($zipEntry));

						if (!empty($fstream)) {
							file_put_contents($fileUnderscoreName, $fstream);
							// Set the rights
							chmod($fileUnderscoreName, 0777);
						}
						//echo "save: ".$fileUnderscoreName."<br />";
					}

					// Close the entry
					zip_entry_close($zipEntry);
				}
			}
			// Close the zip-file
			zip_close($zip);
		}
		return true;
	}

/**
 * This function creates recursive directories if it doesn't already exist
 *
 * @param String The path that should be created
 *
 * @return  void
 */
	public static function createDirs($path) {
		if (!is_dir($path)) {
			$directoryPath = "";
			$directories = explode("/",$path);
			array_pop($directories);
			foreach ($directories as $directory) {
				$directoryPath .= $directory . "/";
				if (!is_dir($directoryPath)) {
					mkdir($directoryPath);
					chmod($directoryPath, 0777);
				} // end if !is_dir
			} // end foreach
		} // end if !is_dir
	}

}