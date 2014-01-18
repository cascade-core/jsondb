{
    "_": "<?php printf('_%c%c}%c',34,10,10);__halt_compiler();?>",
    "output": {
        "done": [
            "index:done"
        ]
    },
    "block:browser_hd": {
        ".block": "core/out/header",
        ".x": 46,
        ".y": 0,
        "text": "JSON Database Browser",
        "slot_weight": 10
    },
    "block:browser": {
        ".block": "jsondb/browser",
        ".x": 215,
        ".y": 146,
        "folder": [
            "admin:path_tail"
        ]
    },
    "block:tree_menu": {
        ".block": "core/out/menu",
        ".x": 705,
        ".y": 15,
        "items": [
            "browser:tree_menu"
        ],
        "active_uri": [
            "router:path"
        ],
        "slot": [
            "browser:slot_tree"
        ]
    },
    "block:table": {
        ".block": "core/out/table",
        ".x": 933,
        ".y": 179,
        "enable": [
            ":not",
            "load:done"
        ],
        "items": [
            "browser:document_list"
        ],
        "config": [
            "browser:document_list_columns"
        ],
        "slot": [
            "browser:slot_content"
        ]
    },
    "block:load": {
        ".block": "jsondb/document/load",
        ".x": 564,
        ".y": 465,
        "enable": [
            "get:document"
        ],
        "json_db": [
            "db:json_db"
        ],
        "folder": [
            "browser:folder"
        ],
        "doc_name": [
            "get:document"
        ]
    },
    "block:get": {
        ".block": "core/in/get",
        ".x": 20,
        ".y": 398
    },
    "block:db": {
        ".block": "jsondb/db",
        ".x": 0,
        ".y": 302
    },
    "block:object_header": {
        ".block": "core/out/header",
        ".x": 932,
        ".y": 333,
        "enable": [
            "load:done"
        ],
        "level": 2,
        "text": [
            "get:document"
        ],
        "slot": [
            "browser:slot_content"
        ],
        "slot_weight": 20
    },
    "block:object_show": {
        ".block": "core/out/print_r",
        ".x": 934,
        ".y": 523,
        "enable": [
            "load:done"
        ],
        "data": [
            "load:doc_data"
        ],
        "slot": [
            "browser:slot_content"
        ],
        "slot_weight": 40
    }
}