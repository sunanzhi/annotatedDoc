<?php
namespace MorsTiin\AnnotatedDoc\Tags;

use phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;
use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use phpDocumentor\Reflection\Types\Context;
use Webmozart\Assert\Assert;

/**
 * class ChangeLog
 */
final class ChangeLog extends BaseTag implements StaticMethod
{
    /**
     * tag name
     *
     * @var string
     */
    protected $name = 'changeLog';

    /**
     * 构造
     * 
     * @param Description $description 
     */
    public function __construct(string $author, string $time, string $event, Description $description = null)
    {
        $this->description = $description;
        $this->author = $author;
        $this->time = $time;
        $this->event = $event;
    }

    /**
     * 创建对象
     *
     * @param string $body
     * @param DescriptionFactory $descriptionFactory
     * @param Context|null $context 
     */
    public static function create(string $body, DescriptionFactory $descriptionFactory = null, Context $context = null): self
    {
        Assert::notNull($descriptionFactory);
        $description = $descriptionFactory->create($body, $context);
        try{
            $content = json_decode($body, true);
        }catch(\Throwable $e){
            $content = [
                'author' => 'system',
                'time' => date('Y-m-d H:i:s'),
                'event' => '文档修改记录错误，请检查注释是否正确'
            ];
        }

        $author = $content['author'] ?? '';
        $time = empty($content['time']) ? '' : date('Y.m.d H:i:s', strtotime(str_replace('.', '-', $content['time']))) ?? '';
        $event = $content['event'] ?? '';

        return new static($author, $time, $event, $description);
    }

    /**
     * 获取作者
     *
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * 获取时间
     *
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }

    /**
     * 获取事件
     *
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * 转字符
     */
    public function __toString(): string
    {
        return (string)$this->description;
    }
}
