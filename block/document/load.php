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

/**
 * Load JSON database document.
 */
class B_jsondb__document__load extends Block {

	protected $inputs = array(
		'json_db' => array(),
		'folder' => array(),
		'doc_name' => array(),
	);

	protected $outputs = array(
		'doc_object' => true,
		'doc_data' => true,
		'*' => true,
		'done' => true,
	);


	public function main()
	{
		$db = $this->in('json_db');
		$folder = $this->in('folder');
		$document_name = $this->in('doc_name');

		$folder = $db->canonizeFolderName($folder);

		$document = $db->openDocument($folder, $document_name);

		$data = $document->getData();
		$this->out('doc_object', $document);
		$this->out('doc_data', $data);

		foreach ($document->getSections() as $section) {
			$this->out('sec_'.$section, $data[$section]);
		}

		$this->out('done', true);
	}

}

