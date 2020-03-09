<?php
namespace MorsTiin\AnnotatedDoc\Tags;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;
use phpDocumentor\Reflection\DocBlock\Tags\TagWithType;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\TypeResolver;
use phpDocumentor\Reflection\Types\Context;
use Webmozart\Assert\Assert;

/**
 * class ResParam
 */
final class ResParam extends TagWithType implements StaticMethod
{
    /**
     * tag name
     *
     * @var string
     */
    protected $name = 'resParam';

    /**
     * 构造
     *
     * @param string|null $variableName
     * @param Type|null $type
     * @param Description $description
     */
    public function __construct(
        ?string $variableName,
        ?Type $type = null,
        Description $description = null) {
        $this->variableName = $variableName;
        $this->type = $type;
        $this->description = $description;
    }

    /**
     * 创建
     *
     * @param string $body
     * @param TypeResolver|null $typeResolver
     * @param DescriptionFactory $descriptionFactory
     * @param Context $context
     * @return self
     */
    public static function create(
        string $body,
        ?TypeResolver $typeResolver = null,
        DescriptionFactory $descriptionFactory = null,
        Context $context = null): self {
        Assert::notNull($descriptionFactory);

        [$firstPart, $body] = self::extractTypeFromBody($body);

        $type = null;
        $parts = preg_split('/(\s+)/Su', $body, 2, PREG_SPLIT_DELIM_CAPTURE);
        Assert::isArray($parts);
        $variableName = '';
        if ($firstPart && $firstPart[0] !== '$') {
            // 类型
            $type = $typeResolver->resolve($firstPart, $context);
        } else {
            // 不是类型 加入part
            array_unshift($parts, $firstPart);
        }
        // 获取返回参数名
        if (isset($parts[0]) && (strpos($parts[0], '$') === 0)) {
            $variableName = array_shift($parts);
            // 删除空格
            array_shift($parts);

            $variableName = substr($variableName, 1);
        }
        $description = $descriptionFactory->create(implode('', $parts), $context);

        return new static($variableName, $type, $description);
    }

    /**
     * 获取返回字段名
     */
    public function getVariableName(): ?string
    {
        return $this->variableName;
    }

    /**
     * 转字符串
     */
    public function __toString(): string
    {
        return (string) $this->description;
    }
}
