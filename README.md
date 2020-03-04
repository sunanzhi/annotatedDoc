# annotatedDoc
php 注释自动生成文档

----

### 效果链接

> http://tp6.sunanzhi.com/doc/index.php?module=makeup&class=Product&method=list

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
moduleList.path | string | 模块的绝对路金 例：`/Users/MorsTiin/sites/projects/annotatedDoc/app/example/api`
moduleList.namespace | string | 模块下接口的命名空间 例：`app\\example\\api`
moduleList.name | string | 模块名 例：`example`
staticUrl | array | 样式链接，考虑到使用者可能用自己项目的layui/jq 
staticUrl.jqueryPath | string | jquery路径 例：`https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js`
staticUrl.layuiJsPath | string | layuiJs路径 例：`https://www.layuicdn.com/layui-v2.5.6/layui.js`
staticUrl.layuiCSSPath | string | layuiCSS路径 例：`https://www.layuicdn.com/layui-v2.5.6/css/layui.css`

----

### 注解tag

----

> @param 参数类型

**使用方式**

```
/**
 * @param integer $userId 用户id
 * @param string $username 用户名
 * @param array $extra 拓展参数
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

> @table 表格类型

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

> @requestExample 请求示例

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

> @returnExample 返回示例

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
 * @author MorsTiin
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

### 特别注意

1、每个tag之后隔开一行
2、有问题邮件或者发issue
3、……

----

### 一个简单示例

```
/**
 * 产品列表分页
 * 
 * @table 返回数据说明
 * key | type | desc
 * page | array | 分页配置
 * page.total | string | 总数
 * page.limit | string | 每页限制
 * page.current | string | 当前页
 * page.lastPage | string | 最后一页
 * items | array | 产品分页数据
 * items.productId | string | 产品id
 * items.name | string | 产品名
 * items.image | string | 产品图
 * items.pcId | string | 产品分类id
 * items.createdTime | string | 产品创建时间
 * items.pcName | string | 产品分类名
 *
 * @param integer $page 页码
 * @param integer $limit 每页限制
 * @return array
 * @requestExample {
 *      "page":1,
 *      "limit":"20"
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
 *              "productId": "29",
 *              "name": "Twelve-Color Animal Eyeshado Palette",
 *              "image": "/upload/image/product/2020011 cdccb0de01bb5643ba8adb0fb60fe7fb.png",
 *              "pcId": "10",
 *              "createdTime": "2020-01-17 18:18:09",
 *              "pcName": "Eyeshadow palette"
 *          }
 *      ]
 *  }
 * 
 * @author sunanzhi <sunanzhi@hotmail.com>
 * @since 2020.2.16
 */
```
