<?php
namespace MorsTiin\AnnotatedDoc\example;

class APITest
{
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
     *              "name": "Twelve-Color Animal Eyeshadow Palette",
     *              "image": "/upload/image/product/20200117/cdccb0de01bb5643ba8adb0fb60fe7fb.png",
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
    public function list(int $page, int $limit)
    {
        return [$page, $limit];
    }
}