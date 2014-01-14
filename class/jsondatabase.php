<?php
/*
 * Copyright (c) 2013, Josef Kufner  <jk@frozen-doe.net>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

namespace JsonDb;

/**
 * Simple JSON file database, built of folders and documents.
 *
 * Folders are namespaces for documents and they are directly translated to 
 * filesystem directories, documents are individual JSON files and sections are 
 * top-level properties in each JSON file.  Each document contain 'info' section 
 * with important metadata about itself (like ctime, mtime, owner and 
 * permissions).
 *
 * Folder is direcotry path, where only lowercase letters, numbers, dash and 
 * dot is allowed. Dash nor dot must not be at the edge of path fragment (next 
 * to slash) and both leading and trailing slash is required.
 *
 * Alternatively folder can be specified as an array of path fragments.
 */
class JsonDatabase {

	protected $base_location;

	const folder_regexp = '/\/([a-z0-9]([a-z0-9_.-]*[a-z0-9])?\/)*/';
	const document_regexp = '/[a-z0-9]([a-z0-9_.-]*[a-z0-9])?/';


	public function __construct($base_location)
	{
		$this->base_location = rtrim($base_location, '/');
	}


	/**
	 * Get location, where root folder is stored
	 */
	public function getBaseLocation()
	{
		return $this->base_location;
	}


	/**
	 * Convert folder name to normalized string form, fixing some errors.
	 */
	public function canonizeFolderName($folder)
	{
		if (!is_array($folder)) {
			$folder = explode('/', (string) $folder);
		}
		$folder = array_filter($folder, function($item) { return $item !== ''; });
		$folder = array_map(function($item) { return preg_replace('/[^a-zA-Z0-9_.-]+/', '-', iconv('UTF-8', 'ASCII//TRANSLIT', $item)); }, $folder);
		$folder = empty($folder) ? '/' : '/'.join('/', $folder).'/';

		return strtolower($folder);
	}


	/**
	 * Verify folder format and return it's location.
	 */
	public function getFolderLocation($folder)
	{
		if (is_array($folder)) {
			$folder = empty($folder) ? '/' : '/'.join('/', $folder).'/';
		}

		if (preg_match(self::folder_regexp, $folder)) {
			return $this->base_location . $folder;
		} else {
			throw new \InvalidArgumentException('Invalid folder "'.$folder.'".');
		}
	}


	/**
	 * List all child folders.
	 */
	public function listFolders($parent_folder)
	{
		$loc = $this->getFolderLocation($parent_folder);
		$list = array();

		foreach ($this->scanDirectory($loc) as $dir) {
			$full_path = $loc.$dir;

			if ($dir[0] == '.' || !is_dir($full_path)) {
				continue;
			}

			if (is_array($parent_folder)) {
				$ns = array_merge($parent_folder, array($dir));
			} else {
				$ns = $parent_folder.$dir.'/';
			}

			$list[$dir] = $ns;
		}

		return $list;
	}


	/**
	 * List entire folder subtree. Returns flat list of all folders.
	 */
	public function listFoldersRecursive($parent_folder, & $list = array(), $prefix = '')
	{
		$loc = $this->getFolderLocation($parent_folder);

		foreach ($this->scanDirectory($loc) as $dir) {
			$full_path = $loc.$dir;

			if ($dir[0] == '.' || !is_dir($full_path) || !is_readable($full_path)) {
				continue;
			}

			if (is_array($parent_folder)) {
				$ns = array_merge($parent_folder, array($dir));
			} else {
				$ns = $parent_folder.$dir.'/';
			}

			$key = $prefix == '' ? $dir : $prefix.'/'.$dir;

			$list[$key] = $ns;

			$this->listFoldersRecursive($ns, $list, $key);
		}

		return $list;
	}


	/**
	 * Returns true when folder exists.
	 */
	public function folderExists($folder)
	{
		$loc = $this->getFolderLocation($folder);
		return is_dir($loc);
	}


	/**
	 * Delete folder. Folder must be empty (no documents or other files).
	 */
	public function deleteFolder($folder)
	{
		$loc = $this->getFolderLocation($folder);
		if (rmdir($loc) === FALSE) {
			$err = error_get_last();
			throw new \Exception('Cannot delete folder: '.$err['message']);
		}
	}


	/**
	 * Create folder using recursive mkdir.
	 */
	public function createFolder($folder)
	{
		$loc = $this->getFolderLocation($folder);
		if (mkdir($loc, 0777, true) === FALSE) {
			$err = error_get_last();
			throw new \Exception('Cannot create folder: '.$err['message']);
		}
	}


	/**
	 * List all documents in the folder.
	 */
	public function listDocuments($folder)
	{
		$loc = $this->getFolderLocation($folder);

		$list = array();

		$d = opendir($loc);
		if ($d === FALSE) {
			$err = error_get_last();
			throw new \DomainException('Cannot read folder: '.$err['message']);
		}

		while (($file = readdir($d)) !== FALSE && $file !== null) {
			$full_path = $loc.$file;

			if ($file[0] == '.' || !is_file($full_path) || substr_compare($file, '.json.php', -9) != 0) {
				continue;
			}

			$list[] = substr($file, 0, -9);
		}
		closedir($d);
		return $list;
	}


	/**
	 * Check if folder has at least one document.
	 */
	public function hasDocuments($folder)
	{
		$loc = $this->getFolderLocation($folder);

		$d = opendir($loc);
		if ($d === FALSE) {
			$err = error_get_last();
			throw new \DomainException('Cannot read folder: '.$err['message']);
		}

		while (($file = readdir($d)) !== FALSE && $file !== null) {
			$full_path = $loc.$file;

			if ($file[0] == '.' || !is_file($full_path) || substr_compare($file, '.json.php', -9) != 0) {
				continue;
			}

			closedir($d);
			return true;
		}
		closedir($d);
		return false;
	}


	/**
	 * Returns location of file where document is (should be) stored.
	 */
	public function getDocumentLocation($folder, $document)
	{
		$ns_loc = $this->getFolderLocation($folder);

		if (!preg_match(self::document_regexp, $document)) {
			throw new \InvalidArgumentException('Invalid document "'.$document.'".');
		}

		return $ns_loc.$document.'.json.php';
	}


	/**
	 * Create new document. Returns instance of JsonDocument.
	 */
	public function createDocument($folder, $document)
	{
		$loc = $this->getDocumentLocation($folder, $document);
		$obj = new JsonDocument($folder, $document, $loc);
		$obj->create();
		return $obj;
	}


	/**
	 * Open existing document. Returns instance of JsonDocument.
	 */
	public function openDocument($folder, $document)
	{
		$loc = $this->getDocumentLocation($folder, $document);
		$obj = new JsonDocument($folder, $document, $loc);
		$obj->open();
		return $obj;
	}


	/**
	 * Returns true when document exists.
	 */
	public function documentExists($folder, $document)
	{
		$loc = $this->getDocumentLocation($folder, $document);
		return is_file($loc);
	}


	/**
	 * Delete document.
	 */
	public function deleteDocument($folder, $document)
	{
		$loc = $this->getDocumentLocation($folder, $document);
		if (unlink($loc) === FALSE) {
			$err = error_get_last();
			throw new Exception('Cannot delete document: '.$err['message']);
		}
	}


	/**
	 * Helper function: scandir() wrapper
	 */
	protected function scanDirectory($loc)
	{
		if (!is_dir($loc)) {
			throw new \DomainException('Cannot open folder: No such directory.');
		}

		if (!is_readable($loc)) {
			throw new \DomainException('Cannot open folder: Directory permission denied.');
		}

		$items = scandir($loc);

		if ($items === FALSE) {
			$err = error_get_last();
			throw new \DomainException('Cannot open folder: '.$err['message']);
		}

		return $items;
	}
}

