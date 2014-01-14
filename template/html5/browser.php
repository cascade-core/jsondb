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

function TPL_html5__jsondb__browser($t, $id, $d, $so)
{
	extract($d);

	echo "<div class=\"jsondb_browser\" id=\"", htmlspecialchars($id), "\">\n";

	// Folder tree
	echo "<div class=\"tree\">\n";
	$t->processSlot($tree_slot);
	echo "</div>\n";

	// Content pane
	echo "<div class=\"content\">\n";
	{
	
		// Current folder
		echo "<div class=\"folder\">";
		printf("<small>Current folder:</small> <tt>%s</tt>", htmlspecialchars($folder));
		echo "</div>\n";

		// Folder content
		$t->processSlot($content_slot);

	}
	echo "</div>\n";

	echo "</div>\n";
}

