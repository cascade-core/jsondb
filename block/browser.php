<?php
/*
 * Copyright (c) 2013, Josef Kufner  <jk@frozen-doe.net>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. Neither the name of the author nor the names of its contributors
 *    may be used to endorse or promote products derived from this software
 *    without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE REGENTS AND CONTRIBUTORS ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
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

