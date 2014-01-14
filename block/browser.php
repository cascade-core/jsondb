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

class B_jsondb__browser extends Block {

	protected $inputs = array(
		'base_location' => DIR_ROOT,
		'folder' => '/',
		'tree_link' => '/admin/devel/jsondb{folder}',
		'document_link' => '/admin/devel/jsondb{folder}?document={document}',
		'slot' => 'default',
		'slot_weight' => 50,
	);

	protected $outputs = array(
		'folder' => true,
		'tree_menu' => true,
		'document_list' => true,
		'document_list_columns' => true,
		'slot_tree' => true,
		'slot_content' => true,
		'done' => true,
	);

	const force_exec = true;


	public function main()
	{
		$base_location = $this->in('base_location');
		$folder = $this->in('folder');
		$tree_link = $this->in('tree_link');
		$document_link = $this->in('document_link');

		$db = new JsonDb\JsonDatabase($base_location);

		$folder = $db->canonizeFolderName($folder);
		$this->out('folder', $folder);

		// Folder tree
		$this->out('tree_menu', $this->buildTreeMenu($db, $tree_link));

		// Layout
		$tree_slot   = $this->fullId().'_tree';
		$content_slot = $this->fullId().'_content';
		$this->templateAddToSlot('head', 'html_head', 60, 'jsondb/html_head', array());
		$this->templateAdd(null, 'jsondb/browser', array(
				'folder' => $folder,
				'tree_slot'    => $tree_slot,
				'content_slot' => $content_slot,
			));
		$this->out('slot_tree', $tree_slot);
		$this->out('slot_content', $content_slot);

		// Folder content
		$this->out('document_list', $this->buildDocumentList($db, $folder, $document_link));
		$this->out('document_list_columns', array(
			'document' => array(
				'type' => 'text',
				'title' => _('Document'),
				'key' => 'document',
				'link' => function($row) use ($document_link) { return filename_format($document_link, $row); },
			),
		));
	}


	protected function buildTreeMenu($db, $tree_link)
	{
		return $this->buildTreeSubMenu($db, '/', $tree_link);
	}


	protected function buildTreeSubMenu($db, $folder, $tree_link)
	{
		$menu = array();

		foreach ($db->listFolders($folder) as $label => $folder) {
			$item = array(
				'title' => $label,
				'link' => filename_format($tree_link, array('folder' => $folder)),
			);

			$children = $this->buildTreeSubMenu($db, $folder, $tree_link);

			if (!empty($children)) {
				$item['children'] = $children;
			}

			if (!empty($children) || $db->hasDocuments($folder)) {
				$menu[] = $item;
			}
		}
		return $menu;
	}


	protected function buildDocumentList($db, $folder, $document_link)
	{
		$list = array();

		foreach($db->listDocuments($folder) as $document) {
			$item = array(
				'folder' => $folder,
				'document' => $document,
			);
			$list[] = $item;
		}

		return $list;
	}
}

