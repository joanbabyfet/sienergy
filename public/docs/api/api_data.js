define({ "api": [
  {
    "type": "post",
    "url": "example/add",
    "title": "添加文章",
    "group": "example",
    "name": "add",
    "version": "1.0.0",
    "description": "<p>添加文章</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "cat_id",
            "description": "<p>分類id</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>标题</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "content",
            "description": "<p>内容</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回示例:",
          "content": "{\n   \"code\": 0,\n   \"msg\": \"保存成功\",\n   \"timestamp\": 1619312083,\n   \"data\": []\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/api/ctl_example.php",
    "groupTitle": "example"
  },
  {
    "type": "post",
    "url": "example/delete",
    "title": "删除文章",
    "group": "example",
    "name": "delete",
    "version": "1.0.0",
    "description": "<p>删除文章</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "ids",
            "description": "<p>id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回示例:",
          "content": "{\n   \"code\": 0,\n   \"msg\": \"刪除成功\",\n   \"timestamp\": 1619311833,\n   \"data\": []\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/api/ctl_example.php",
    "groupTitle": "example"
  },
  {
    "type": "post",
    "url": "example/detail",
    "title": "获取文章详请",
    "group": "example",
    "name": "detail",
    "version": "1.0.0",
    "description": "<p>获取文章详请</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回示例:",
          "content": "{\n    \"code\": 0,\n    \"msg\": \"success\",\n    \"timestamp\": 1633067402,\n    \"data\": {\n        \"id\": \"3f4e27e38e76f16cca5b1ac279411688\",\n        \"cat_id\": 1,\n        \"title\": \"博士\",\n        \"content\": \"衣袋里，藍皮阿五，睡眼蒙朧的跟定他，問他可以責備的。」 「我想：他和趙太太也正是雙十節之後，雖然是出雜誌，名目。孔子曰，“沒有人說，「竊書！……” “救命，移植到他家的秤又是橫笛，很像久餓的人，便給他穿上棉襖；現在想念水生卻又粗又笨重，便坐在床上就要喫飯的人，都埋着死刑宣告討論，我家來要錢，一千字也不行的拼法寫他為阿Q抓出衙門裏面的屋子裏，替他宣傳，而且奇怪，似乎看翻筋斗。」 小栓也趁着熱鬧，拚。\",\n        \"img\": \"\",\n        \"file\": \"\",\n        \"is_hot\": 0,\n        \"sort\": 0,\n        \"status\": 1,\n        \"create_time\": 821712958,\n        \"create_user\": \"0\",\n        \"update_time\": 0,\n        \"update_user\": \"0\",\n        \"delete_time\": 0,\n        \"delete_user\": \"0\",\n        \"status_dis\": \"啟用\",\n        \"create_time_dis\": \"1996/01/15 21:35\",\n        \"create_user_dis\": \"0\",\n        \"img_dis\": [],\n        \"img_url_dis\": [],\n        \"file_dis\": [],\n        \"file_url_dis\": []\n    }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/api/ctl_example.php",
    "groupTitle": "example"
  },
  {
    "type": "post",
    "url": "example/edit",
    "title": "修改文章",
    "group": "example",
    "name": "edit",
    "version": "1.0.0",
    "description": "<p>修改文章</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "cat_id",
            "description": "<p>分類id</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>标题</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "content",
            "description": "<p>内容</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回示例:",
          "content": "{\n    \"code\": 0,\n    \"msg\": \"保存成功\",\n    \"timestamp\": 1619312083,\n    \"data\": []\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/api/ctl_example.php",
    "groupTitle": "example"
  },
  {
    "type": "post",
    "url": "example",
    "title": "获取文章列表",
    "group": "example",
    "name": "index",
    "version": "1.0.0",
    "description": "<p>获取文章列表</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page_size",
            "description": "<p>每页显示几条</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "page_no",
            "description": "<p>第几页</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "cat_id",
            "description": "<p>分類id</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "返回示例:",
          "content": "{\n    \"code\": 0,\n    \"msg\": \"success\",\n    \"timestamp\": 1633067192,\n    \"data\": {\n        \"data\": [\n            {\n            \"id\": \"cba20abff7d9481bf418ee8697014806\",\n            \"cat_id\": 1,\n            \"title\": \"博士\",\n            \"content\": \"這並沒有空，便都上岸。母親又說是要到他也敢出言無狀麽？那時是連日的早在我們便接了孩子還有什麼罷。大家也仿佛從這一定是皇帝已經投降，是社戲了。”鄒七嫂，也覺得自己。他身上，下了。一個巡警，五行缺土，但總免不了偶然忘卻了，我于是想提倡文藝，于是我自己的名字會和“老”字面上，應該的。我的腦裡忽然又恨到七斤嫂沒有？——便好了。 夜間，我這《阿Q都早忘卻了。他的兒子的，三三兩兩的人叢去。”“老”字聯結起來。\",\n            \"is_hot\": 0,\n            \"status\": 1,\n            \"sort\": 0,\n            \"create_user\": \"0\",\n            \"create_time\": 1593349998,\n            \"status_dis\": \"啟用\",\n            \"create_time_dis\": \"2020/06/28 21:13\",\n            \"create_user_dis\": \"0\",\n            \"img_dis\": [],\n            \"img_url_dis\": [],\n            \"file_dis\": [],\n            \"file_url_dis\": []\n            }\n        ],\n    \"total_page\": 2,\n    \"total\": 12\n    }\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/api/ctl_example.php",
    "groupTitle": "example"
  }
] });
