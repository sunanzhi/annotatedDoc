<?php
namespace MorsTiin\AnnotatedDoc;

use MorsTiin\AnnotatedDoc\Tags\ChangeLog;
use MorsTiin\AnnotatedDoc\Tags\ResParam;
use phpDocumentor\Reflection\DocBlock\Tags\Author;
use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use phpDocumentor\Reflection\DocBlock\Tags\Link;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use phpDocumentor\Reflection\DocBlock\Tags\Since;
use phpDocumentor\Reflection\DocBlockFactory;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;

/**
 * 处理文档内容
 */
class AnnotationDoc
{
    /**
     * 方法参数
     *
     * @var array
     */
    private $methodParameters = [];

    /**
     * 请求链接
     *
     * @var string
     */
    private $requestUrl = '';

    /**
     * 参数
     *
     * @var array
     */
    private $params = [];

    /**
     * 返回数据说明
     *
     * @var array
     */
    private $resParams = [];

    /**
     * 表格
     *
     * @var array
     */
    private $table = [];

    /**
     * 请求示例
     *
     * @var array
     */
    private $requestExample = [];

    /**
     * 返回示例
     *
     * @var array
     */
    private $returnExample = [];

    /**
     * 作者
     *
     * @var string
     */
    private $author = '';

    /**
     * 时间
     *
     * @var string
     */
    private $since = '';

    /**
     * 查看链接
     *
     * @var array
     */
    private $link = [];

    /**
     * 修改记录
     *
     * @var array
     */
    private $changeLog = [];

    /**
     * 请求方式
     *
     * @var array
     */
    private $method = ['POST'];

    /**
     * 游客 true:游客可以访问 false:需要登陆
     *
     * @var boolean
     */
    private $guest = false;

    /**
     * 权限 true:需要权限 false:不需要权限
     *
     * @var boolean
     */
    private $rank = false;

    /**
     * 缓存时间
     *
     * @var integer
     */
    private $cache = 0;

    /**
     * 处理类文档内容
     * 
     * @param ReflectionClass $class 类
     * @return string
     */
    public static function handleClassComment(ReflectionClass $class): string
    {
        try{
            $docComment = $class->getDocComment();
            $factory  = DocBlockFactory::createInstance(Config::getInstance()->getExtraTags());
            $docblock = $factory->create($docComment);
            // 标题
            $summary = $docblock->getSummary();
            // 描述
            $description = $docblock->getDescription()->render();
        }catch(\Throwable $e){
            return '';
        }

        return $summary.' '.$description;
    }

    /**
     * 处理方法文档内容
     *
     * @param ReflectionMethod $method 方法
     * @return array
     */
    public function handleMethodComment(ReflectionMethod $method): array
    {
        $this->handleMethodParameters($method);
        // 文档内容
        $docComment = $method->getDocComment();
        if(empty($docComment)){
            return [];
        }
        $factory  = DocBlockFactory::createInstance(Config::getInstance()->getExtraTags());
        $docblock = $factory->create($docComment);
        // 标题
        $summary = $docblock->getSummary();
        // 描述
        $description = $docblock->getDescription()->render();
        // 获取所有标签
        $tags = $docblock->getTags();
        foreach($tags as $tag){
            $tagName = $tag->getName();
            if(!in_array($tagName, Config::getInstance()->availableTags)){
                continue;
            }
            $method = 'handle'.$tagName;
            $this->$method($tag);
        }
        array_multisort(array_column($this->changeLog, 'time'), SORT_DESC, $this->changeLog);

        return [
            'summary' => $summary,
            'description' => $description,
            'method' => $this->method,
            'requestUrl' => $this->requestUrl,
            'params' => $this->params,
            'resParams' => $this->resParams,
            'table' => $this->table,
            'author' => $this->author,
            'since' => $this->since,
            'link' => $this->link,
            'changeLog' => $this->changeLog,
            'requestExample' => $this->requestExample,
            'returnExample' => $this->returnExample,
            'guest' => $this->guest,
            'rank' => $this->rank,
            'cache' => $this->cache,
        ];
    }

    /**
     * 处理方法参数
     *
     * @param ReflectionMethod $method 方法
     * @return void
     */
    private function handleMethodParameters(ReflectionMethod $method)
    {
        $methodPparams = $method->getParameters();
        foreach ($methodPparams as $param) {
            $paramName = $param->getName();
            $reflectionType = $param->getType();
            assert($reflectionType instanceof ReflectionNamedType);
            $this->methodParameters[$paramName] = [
                'default' => $param->isDefaultValueAvailable(),
                'defaultValue' => $param->isDefaultValueAvailable() ? $param->getDefaultValue() : '无',
                'name' => $paramName,
                'type' => $reflectionType->getName(),
            ];
        }
    }

    /**
     * 处理表格类型数据
     *
     * @param BaseTag $tag 标签
     * @return void
     */
    private function handleTable(BaseTag $tag)
    {
        $content = $tag->__toString();
        if(empty($content)){
            return ;
        }
        $contents = explode("\n", $content);
        $title = '';
        $thead = [];
        $tbody = [];
        foreach($contents as $key => $content){
            if($key == 0){
                $title = $content;
                continue;
            }
            if($key == 1){
                $thead = explode('|', $content);
                continue;
            }
            $tbody[] = explode('|', $content);
        }
        $this->table[] = [
            'title' => $title,
            'thead' => $thead,
            'body' => $tbody,
        ];
    }

    /**
     * 处理请求参数类型
     *
     * @param Param $tag
     * @return void
     */
    private function handleParam(Param $tag)
    {
        $name = $tag->getVariableName();
        $this->params[] = [
            'name' => $name,
            'type' => $tag->getType()->__toString(),
            'default' => $this->methodParameters[$name]['default'] ? '是' : '否',
            'defaultValue' => $this->methodParameters[$name]['defaultValue'],
            'desc' => $tag->getDescription()->__toString()
        ];
    }

    /**
     * 处理返回字段类型
     *
     * @param ResParam $tag
     * @return void
     */
    private function handleResParam(ResParam $tag)
    {
        $name = $tag->getVariableName();
        $this->resParams[] = [
            'name' => $name,
            'type' => $tag->getType()->__toString(),
            'desc' => $tag->getDescription()->__toString()
        ];
    }

    /**
     * 处理请求示例
     *
     * @param BaseTag $tag
     * @return void
     */
    private function handleRequestExample(BaseTag $tag)
    {
        $this->requestExample[] = $tag->getDescription()->__toString();
    }

    /**
     * 处理请求示例
     *
     * @param BaseTag $tag
     * @return void
     */
    private function handleReturnExample(BaseTag $tag)
    {
        $this->returnExample[] = $tag->getDescription()->__toString();
    }

    /**
     * 处理作者
     *
     * @param Author $tag
     * @return void
     */
    private function handleAuthor(Author $tag)
    {
        $this->author = $tag->__toString();
    }

    /**
     * 处理since
     *
     * @param Since $tag
     * @return void
     */
    private function handleSince(Since $tag)
    {
        $this->since = $tag->__toString();
    }

    /**
     * 处理请求链接
     *
     * @param BaseTag $tag
     * @return void
     */
    private function handleRequestUrl(BaseTag $tag)
    {
        $this->requestUrl = $tag->getDescription()->__toString();
    }

    /**
     * 处理请查看链接
     *
     * @param Link $tag
     * @return void
     */
    private function handleLink(Link $tag)
    {
        $this->link[] = $tag->getLink();
    }

    /**
     * 处理修改日记记录
     *
     * @param ChangeLog $tag
     * @return void
     */
    private function handleChangeLog(ChangeLog $tag)
    {
        $this->changeLog[] = [
            'author' => $tag->getAuthor(),
            'time' => $tag->getTime(),
            'event' => $tag->getEvent()
        ];
    }

    /**
     * post请求
     *
     * @param BaseTag $tag
     * @return void
     */
    private function handlePost(BaseTag $tag)
    {
        $this->method[] = 'POST';
    }

    /**
     * get请求
     *
     * @param BaseTag $tag
     * @return void
     */
    private function handleGet(BaseTag $tag)
    {
        $this->method[] = 'GET';
    }

    /**
     * put请求
     *
     * @param BaseTag $tag
     * @return void
     */
    private function handlePut(BaseTag $tag)
    {
        $this->method[] = 'PUT';
    }

    /**
     * delete请求
     *
     * @param BaseTag $tag
     * @return void
     */
    private function handleDelete(BaseTag $tag)
    {
        $this->method[] = 'DELETE';
    }

    /**
     * options请求
     *
     * @param BaseTag $tag
     * @return void
     */
    private function handleOptions(BaseTag $tag)
    {
        $this->method[] = 'OPTIONS';
    }

    /**
     * trace请求
     *
     * @param BaseTag $tag
     * @return void
     */
    private function handleTrace(BaseTag $tag)
    {
        $this->method[] = 'TRACE';
    }

    /**
     * connect请求
     *
     * @param BaseTag $tag
     * @return void
     */
    private function handleConnect(BaseTag $tag)
    {
        $this->method[] = 'CONNECT';
    }

    /**
     * 游客可以访问
     *
     * @param BaseTag $tag
     * @return void
     */
    private function handleGuest(BaseTag $tag)
    {
        $this->guest = true;
    }

    /**
     * 权限
     *
     * @param BaseTag $tag
     * @return void
     */
    private function handleRank(BaseTag $tag)
    {
        $this->rank = $tag->getDescription()->__toString();
    }

    /**
     * 缓存
     *
     * @param BaseTag $tag
     * @return void
     */
    private function handleCache(BaseTag $tag)
    {
        $this->cache = $tag->getDescription()->__toString();
    }
}
