<?php
namespace MorsTiin\AnnotatedDoc;

use Closure;
use MorsTiin\AnnotatedDoc\Tags\ChangeLog;
use MorsTiin\AnnotatedDoc\Tags\ResParam;

/**
 * 配置文件
 */
class Config
{
    /**
     * 静态单例
     *
     * @var Config
     */
    private static $_instance = NULL;

    /**
     * 文档标题
     *
     * @var string
     */
    public $title = 'MorsTiin API 文档';

    /**
     * 默认模块
     *
     * @var string
     */
    public $defaultModule = 'example';

    /**
     * 默认类
     *
     * @var string
     */
    public $defaultClass = '';

    /**
     * 默认方法
     *
     * @var string
     */
    public $defaultMethod = '';

    /**
     * 模块列表
     *
     * @var array
     */
    public $moduleList = [
        ['path' => 'app/example/api', 'namespace' => 'app\\example\\api', 'name' => 'example']
    ];

    /**
     * 禁止层级
     *
     * @var array
     */
    public $forbiddenLevel = [
        '.', '..', '...', '....', '.....', '......'
    ];

    /**
     * 备案号
     *
     * @var string
     */
    public $miitbeian = '';

    /**
     * 处理 requestUrl 回调
     *
     * @var Closure 
     */
    public $requestUrlCallback = null;

    /**
     * 静态样式url
     *
     * @var array
     */
    public $staticUrl = [
        'jqueryPath' => '//static.sunanzhi.com/jquery/jquery-3.4.1.min.js',
        'layuiJsPath' => '//static.sunanzhi.com/layui/layui-v2.5.6/layui.js',
        'layuiCSSPath' => '//static.sunanzhi.com/layui/layui-v2.5.6/css/layui.css'
    ];

    /**
     * 可用标签
     *
     * @var array
     */
    public $availableTags = [
        'post', 
        'get', 
        'put', 
        'delete', 
        'options', 
        'trace', 
        'connect', 
        'param', 
        'resParam', 
        'author', 
        'since', 
        'link', 
        'requestExample', 
        'returnExample', 
        'requestUrl', 
        'table', 
        'changeLog',
        'guest',
        'rank',
        'cache',
    ];

    /**
     * 左边菜单宽度
     *
     * @var string
     */
    public $leftPadding = '200px';

    /**
     * 规范链接
     *
     * @var string
     */
    public $docSpecificationUrl = '';

    /**
     * 切换方法名显示 名称/概要 false=显示方法名称 true=显示方法概要
     *
     * @var boolean
     */
    public $switchMethod = false;

    private $extraTags = [
        'resParam' => ResParam::class, 
        'changeLog' => ChangeLog::class
    ];

    /**
     * 设置拓展tags
     *
     * @param array $tags
     * @return void
     */
    public function setExtraTags(array $tags)
    {
        $this->extraTags = array_merge($this->extraTags, $tags);
    }

    /**
     * 获取拓展tags
     *
     * @return array
     */
    public function getExtraTags()
    {
        return $this->extraTags;
    }

    /**
     * 处理请求url
     *
     * @param string $requestUrl 系统通过路径生成
     * @param Closure $callback 回调
     * @return string
     */
    public function handleRequestUrl(string $requestUrl, Closure $callback)
    {
        return $callback($requestUrl);
    }

    /**
     * 获取单例
     *
     * @return Config
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}