<?php
namespace MorsTiin\AnnotatedDoc\Tests\example;

/**
 * 类名
 * 
 * class APITest
 */
class APITest
{
    /**
     * 产品列表
     * 
     * 我是一个简单的描述
     * 我还可以换行
     * 我还可以换行
     * 我还可以换行
     * 
     * @requestUrl http://annotateddoc.sunanzhi.com/makeup/Product/list
     * 
     * @table buttonKey其他特殊字段说明
     * value | desc
     * btnTest | 测试
     * btnTest | 测试
     * btnTest | 测试
     * btnTest | 测试
     * btnTest | 测试
     *
     * @param integer $page 页码
     * @param integer $limit 每页限制
     * @resParam array $page 分页配置
     * @resParam string $page.total 总数
     * @resParam string $page.limit 每页限制
     * @resParam string $page.current 当前页
     * @resParam string $page.lastPage 最后一页
     * @resParam array $items 产品分页数据
     * @resParam string $items.productId 产品id
     * @resParam string $items.name 产品名
     * @resParam string $items.image 产品图
     * @resParam string $items.pcId 产品分类id
     * @resParam string $items.createdTime 产品创建时间
     * @resParam string $items.pcName 产品分类名
     * @return array
     * @requestExample {
     *      "page":1,
     *      "limit":"20"
     *  }
     * 
     * @requestExample {
     *      "page":2,
     *      "limit":"10"
     *  }
     * 
     * @requestExample {
     *      "page":3,
     *      "limit":"30"
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
     *              "name": "Twelve-Color Animal Eyeshadow Palette",
     *              "image": "/upload/image/product/20200117/cdccb0de01bb5643ba8adb0fb60fe7fb.png",
     *              "pcId": "10",
     *              "createdTime": "2020-01-17 18:18:09",
     *              "pcName": "Eyeshadow palette"
     *          }
     *      ]
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
     *              "name": "Twelve-Color Animal Eyeshadow Palette"
     *          }
     *      ]
     *  }
     * 
     * @author sunanzhi <sunanzhi@hotmail.com>
     * @since 2020.2.16
     * 
     * @link https://github.com/sunanzhi/annotatedDoc
     * @link https://github.com/sunanzhi/annotatedDoc
     * @changeLog {"author": "sunanzhi", "time": "2020.03.10", "event": "更新案例"}
     * @changeLog {"author": "sunanzhi", "time": "2020.03.10", "event": "更新案例"}
     */
    public function list(int $page, int $limit)
    {
        return [$page, $limit];
    }
}