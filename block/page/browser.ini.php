;<?php exit(); __HALT_COMPILER; ?>


[output]
done[] = "index:done"

[block:browser_hd]
.block = "core/out/header"
.x = 0
.y = 0
text = "JSON Database Browser"
slot_weight = "10"

[block:browser]
.block = "jsondb/browser"
.x = 230
.y = 24
folder[] = "admin:path_tail"

[block:tree_menu]
.block = "core/out/menu"
.x = 557
.y = 20
items[] = "browser:tree_menu"
active_uri[] = "router:path"
slot[] = "browser:slot_tree"

[block:table]
.block = "core/out/table"
.x = 557
.y = 208
items[] = "browser:document_list"
config[] = "browser:document_list_columns"
slot[] = "browser:slot_content"


; vim:filetype=dosini:
