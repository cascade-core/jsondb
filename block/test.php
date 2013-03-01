<?php
/*
 * Copyright (c) 2010, Josef Kufner  <jk@frozen-doe.net>
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

class B_jsondb__test extends Block {

	protected $inputs = array(
	);

	protected $outputs = array(
		'done' => true,
	);


	public function main()
	{
		try {
			$db = new JsonDatabase\JsonDatabase(DIR_ROOT);
			debug_dump($db->getBaseLocation(), 'Base location');

			debug_dump($db->listFolders('/'), 'Base folders');
			debug_dump($db->listFoldersRecursive('/'), 'Base folders (recursive)');

			debug_dump($db->listDocuments('/data/'), 'Data documents');

			if ($db->documentExists('/data/', 'hello')) {
				$db->deleteDocument('/data/', 'hello');
			}

			$obj = $db->createDocument('/data/', 'hello');
			$obj->foo['bar'] = 'world';
			debug_dump($obj, 'Document');
			$obj->close();
			debug_dump((file_get_contents($db->getDocumentLocation('/data/', 'hello'))), 'file');

			$obj = $db->openDocument('/data/', 'hello');
			debug_dump($obj, 'Document reloaded');

		}
		catch (Exception $ex) {
			debug_dump($ex, '!!! Exception');
		}
	}
}

