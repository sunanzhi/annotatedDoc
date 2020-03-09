# annotatedDoc
php 注释自动生成文档

----

### 效果链接

> http://annotateddoc.sunanzhi.com/doc/index.php?module=makeup&class=Product&method=list

----

### 安装方式

> composer require sunanzhi/annotateddoc

----

### 使用方式

**在public文件夹下新建doc文件夹，doc文件夹命名一个`index.php`文件（这一步支持随意化，看项目的路径访问）**
```php
<?php
require_once "../../vendor/autoload.php";

use MorsTiin\AnnotatedDoc\Config;
use MorsTiin\AnnotatedDoc\Template;

// 单例配置获取
$config = Config::getInstance();
// 模块信息配置
$config->moduleList = [
    ['path' => __DIR__.'/../../app/example/api', 'namespace' => 'app\\example\\api', 'name' => 'example'],
    ['path' => __DIR__.'/../../app/makeup/api', 'namespace' => 'app\\makeup\\api', 'name' => 'makeup'],
];

echo (new Template())->show();
```

**使用包内案例**

```php
<?php
require_once "../../vendor/sunanzhi/annotateddoc/tests/doc.php";

```
----

### 配置Config说明

key | type | desc
--- | ---- | ----
defaultModule | string | 默认模块 例：`example`
defaultClass | string | 默认类
defaultMethod | string | 默认方法
moduleList | array | 模块列表
moduleList.path | string | 模块的绝对路径 例：`/Users/MorsTiin/sites/projects/annotatedDoc/app/example/api`
moduleList.namespace | string | 模块下接口的命名空间 例：`app\\example\\api`
moduleList.name | string | 模块名 例：`example`
staticUrl | array | 样式链接，考虑到使用者可能用自己项目的layui/jq 
staticUrl.jqueryPath | string | jquery路径 例：`https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js`
staticUrl.layuiJsPath | string | layuiJs路径 例：`https://www.layuicdn.com/layui-v2.5.6/layui.js`
staticUrl.layuiCSSPath | string | layuiCSS路径 例：`https://www.layuicdn.com/layui-v2.5.6/css/layui.css`
availableTags | array | 默认可用标签

----

### tags 列表

标签名 | 描述 | 是否支持多个
----- | ---- | ----
param | 请求参数 | 是
resParam | 返回字段 | 是
requestUrl | 请求url ｜ 否
requestExample | 请求示例 | 是
returnExample | 返回示例 | 是
table | 表格 | 是
author | 作者 | 否
since | 创建时间 | 否
link | 引用链接 | 是
changeLog | 变更记录 | 否

### 注解tag

----

> @param 参数，支持多个

**使用方式**

```
/**
 * @param integer $userId 用户id
 * @param string $username 用户名
 * @param array $extra 拓展参数
 */
```
----

> @resParam 返回结果字段，支持多个

**使用方式**

```
/**
 * @resParam integer $userId 用户id
 * @resParam string $username 用户名
 * @resParam array $extra 拓展参数
 * @resParam string $extra.key 拓展参数1
 * @resParam string $extra.value 拓展参数2
 */
```
----

> @requestUrl 请求链接

**使用方式**

**注意：如果不填写默认按照 `模块/类名/方法` 方式显示**

```
/**
 * @requestUrl https://www.domain.com/module/api/get
 */
```
----

> @table 表格类型，支持多个

**使用方式**

```
/**
 * @table 我是表格 
 * key | type | desc
 * page | array | 分页配置
 * page.total | string | 总数
 * page.limit | string | 每页限制
 * items | array | 产品分页数据
 * items.productId | string | 产品id
 */
```

----

> @requestExample 请求示例，支持多个

**使用方式 json数据格式**

```
/**
 * @requestExample {
 *      "userId":1,
 *      "username":"MorsTiin",
 *      "extra": {
 *          "key1": "value1",
 *          "key2": "value2",
 *      }
 *  }
 */
```

> @returnExample 返回示例，支持多个

**使用方式 json数据格式**

```
/**
 * @returnExample {
 *      "key1": "value1",
 *      "key2": [
 *          "value2",
 *          "value2"
 *      ],
 *      "key3": {
 *          "key3-1": "value3-1",
 *          "key3-2": "value3-2"
 *      },
 *      "key4": [
 *          {
 *              "key4-1": "value4-1"
 *          },
 *          {
 *              "key4-2": "value4-2"
 *          }
 *      ]
 *  }
 */
```

----

> @author 作者

**使用方式**

```
/**
 * @author MorsTiin <AnnotatedDoc@Test.com>
 */
```

----

> @since 接口创建时间

**使用方式**

```
/**
 * @since 2020.2.20
 */
```

----

> @link 参考链接，支持多个

**使用方式**

```
/**
 * @link https://github.com/sunanzhi/annotatedDoc
 */
```

----

> @changeLog 变更日志，支持多个

**使用方式**

```
/**
 * @changeLog {"author": "sunanzhi@hotmail.com",  "time": "2020-03-09 11:16:00", "event":"更换文档注释"}
 */
```

----

### 一个简单示例，仅供参考，如有问题邮件或发布issue

```
/**
 * 我是示例标题，首行默认标题
 * 
 * 这是一段接口描述，如有则正常显示
 * 
 * @requestUrl 我是请求链接，可以不用填写
 * 
 * @table 我是表格标题
 * 表头 | 表头 
 * key1 | value1
 * key2 | value2
 * key3 | value3
 * key4 | value4
 * 
 * @param string $param 我是参数描述
 * @param string $param 我是参数描述
 *
 * @resParam string $param 我是返回参数描述
 * @resParam string $param 我是返回参数描述
 * @resParam string $param 我是返回参数描述
 *
 * @return array
 *
 * @requestExample {
 *      "key1":"我是请求示例",
 *      "value1":"我是请求示例"
 *  }
 * @requestExample {
 *      "key2":"我是请求示例",
 *      "value2":"我是请求示例"
 *  }
 *  
 * @returnExample {
 *      "page": {
 *          "total": "4",
 *          "limit": "1",
 *          "current": "1",
 *          "lastPage": "4"
 *      },
 *      "items": [
 *          {
 *              "key1": "我是返回示例"
 *          },
 *          {
 *              "key2": "我是返回示例"
 *          }
 *      ]
 *  }
 * @returnExample {
 *      "page": {
 *          "total": "4",
 *          "limit": "1",
 *          "current": "1",
 *          "lastPage": "4"
 *      },
 *      "items": [
 *          {
 *              "key1": "我是返回示例"
 *          },
 *          {
 *              "key2": "我是返回示例"
 *          }
 *      ]
 *  }
 * 
 * @author sunanzhi <sunanzhi@hotmail.com>
 * @since 2020.2.16
 * @changeLog {"author": "sunanzhi@hotmail.com", "time": "2020.03.09 12:12:12", "event": "接口更换文档"}
 * @changeLog {"author": "sunanzhi@hotmail.com", "time": "2020.03.10 12:12:12", "event": "接口更换文档"}
 * @link https://github.com/sunanzhi/annotatedDoc
 * @link https://github.com/sunanzhi/annotatedDoc
 */
```
