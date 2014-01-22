{
    "_": "<?php printf('_%c%c}%c',34,10,10);__halt_compiler();?>",
    "output": {
        "done": [
            "index:done"
        ]
    },
    "blocks": {
        "browser_hd": {
            "block": "core/out/header",
            "x": 46,
            "y": 0,
            "in_val": {
                "text": "JSON Database Browser",
                "slot_weight": 10
            }
        },
        "browser": {
            "block": "jsondb/browser",
            "x": 215,
            "y": 146,
            "in_con": {
                "folder": [
                    "admin",
                    "path_tail"
                ]
            }
        },
        "tree_menu": {
            "block": "core/out/menu",
            "x": 705,
            "y": 15,
            "in_con": {
                "items": [
                    "browser",
                    "tree_menu"
                ],
                "active_uri": [
                    "router",
                    "path"
                ],
                "slot": [
                    "browser",
                    "slot_tree"
                ]
            }
        },
        "table": {
            "block": "core/out/table",
            "x": 933,
            "y": 179,
            "in_con": {
                "enable": [
                    ":not",
                    "load",
                    "done"
                ],
                "items": [
                    "browser",
                    "document_list"
                ],
                "config": [
                    "browser",
                    "document_list_columns"
                ],
                "slot": [
                    "browser",
                    "slot_content"
                ]
            }
        },
        "load": {
            "block": "jsondb/document/load",
            "x": 564,
            "y": 465,
            "in_con": {
                "enable": [
                    "get",
                    "document"
                ],
                "json_db": [
                    "db",
                    "json_db"
                ],
                "folder": [
                    "browser",
                    "folder"
                ],
                "doc_name": [
                    "get",
                    "document"
                ]
            }
        },
        "get": {
            "block": "core/in/get",
            "x": 20,
            "y": 398
        },
        "db": {
            "block": "jsondb/db",
            "x": 0,
            "y": 302
        },
        "object_header": {
            "block": "core/out/header",
            "x": 932,
            "y": 333,
            "in_con": {
                "enable": [
                    "load",
                    "done"
                ],
                "text": [
                    "get",
                    "document"
                ],
                "slot": [
                    "browser",
                    "slot_content"
                ]
            },
            "in_val": {
                "level": 2,
                "slot_weight": 20
            }
        },
        "object_show": {
            "block": "core/out/print_r",
            "x": 934,
            "y": 523,
            "in_con": {
                "enable": [
                    "load",
                    "done"
                ],
                "data": [
                    "load",
                    "doc_data"
                ],
                "slot": [
                    "browser",
                    "slot_content"
                ]
            },
            "in_val": {
                "slot_weight": 40
            }
        }
    }
}