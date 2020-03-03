<?php
namespace MorsTiin\AnnotatedDoc;

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
     * 静态样式url
     *
     * @var array
     */
    public $staticUrl = [
        'jqueryPath' => 'http://general.sunanzhi.com/jquery/jquery-3.4.1.min.js',
        'layuiJsPath' => 'http://general.sunanzhi.com/layui/layui-v2.5.6/layui.js',
        'layuiCSSPath' => 'http://general.sunanzhi.com/layui/layui-v2.5.6/css/layui.css'
    ];

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