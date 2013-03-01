{
	"_": "<?php printf('_%c%c}%c',34,10,10);__halt_compiler();?>",
	"main_menu": {
		"devel": {
			"children": {
				"jsondb": {
					"title": "JSON Database",
					"link": "/admin/devel/jsondb",
					"children": {
						"test": {
							"title": "Tests",
							"link": "/admin/devel/jsondb-test"
						}
					}
				}
			}
		}
	},
	"routes": {
		"/devel/jsondb": {
			"title": "JSON Database browser",
			"block": "jsondb/page/browser",
			"connections": {
			}
		},
		"/devel/jsondb/**": {
			"title": "JSON Database browser",
			"block": "jsondb/page/browser",
			"connections": {
			}
		},
		"/devel/jsondb-test": {
			"title": "JSON Database test",
			"block": "jsondb/test",
			"connections": {
			}
		}
	}
}

