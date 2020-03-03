<?php
namespace MorsTiin\AnnotatedDoc;

use ReflectionMethod;

/**
 * 处理文档内容
 */
class AnnotationDoc
{
    /**
     * 处理文档内容
     *
     * @param ReflectionMethod $method
     * @return array
     */
    public static function handleComment(ReflectionMethod $method): array
    {
        $doc = explode("\n", $method->getDocComment());
        $methodPparams = $method->getParameters();
        $paramsWithMethod = [];
        // 方法参数
        foreach ($methodPparams as $param) {
            $paramName = $param->getName();
            $paramsWithMethod[$paramName] = [
                'default' => $param->isDefaultValueAvailable(),
                'defaultValue' => $param->isDefaultValueAvailable() ? $param->getDefaultValue() : 'NULL',
                'name' => $paramName,
                'type' => $param->getType()->getName(),
            ];
        }
        $params = [];
        $otherComment = [];
        $example = [];
        $table = [];
        $desc = [];
        for ($i = 0;; $i++) {
            // 删除首尾空格
            $value = trim($doc[$i]);
            if ($value == '') {
                break;
            }
            // 无用注解
            if (in_array($value, ['/**', '*'])) {
                continue;
            }
            // 注释结束判断
            if ($value == '*/') {
                break;
            }
            // 删除首位*字符
            $value = trim(substr($value, 1));
            // 获取tag
            $tagStartPosition = strpos($value, '@');
            $tagEndPosition = 0;
            if ($tagStartPosition === 0) {
                $tagEndPosition = strpos($value, ' ');
                $tag = substr($value, 1, $tagEndPosition - 1);
            } else {
                $tag = '';
            }
            if ($tag == '') {
                $desc[] = $value;
            }
            switch ($tag) {
                case 'param': // 参数
                    $paramArr = explode(' ', substr($value, $tagEndPosition + 1));
                    $name = substr($paramArr[1], 1);
                    if (isset($paramsWithMethod[$name])) {
                        $params[] = [
                            'name' => $name,
                            'type' => $paramsWithMethod[$name]['type'],
                            'default' => $paramsWithMethod[$name]['default'] ? '是' : '否',
                            'defaultValue' => $paramsWithMethod[$name]['defaultValue'],
                            'desc' => $paramArr[2],
                        ];
                    }
                    break;
                case 'author':
                case 'since':
                case 'see':
                    $otherComment[$tag] = substr($value, $tagEndPosition + 1);
                    break;
                case 'requestExample':
                case 'returnExample':
                    $i++;
                    $exampleJson = '';
                    for ($i;; $i++) {
                        $value = trim($doc[$i]);
                        if (in_array($value, ['*/', '*'])) {
                            $i--;
                            $exampleJson = "{" . (string) $exampleJson;
                            break;
                        }
                        $value = trim(substr($value, 1));
                        $exampleJson .= $value;
                    }
                    $example[$tag] = $exampleJson;
                    break;
                case 'table':
                    $tableTitle = substr($value, $tagEndPosition + 1);
                    $thead = [];
                    $i++;
                    $iTmp = $i;
                    $tableTmp = [];
                    for ($i;; $i++) {
                        $value = trim($doc[$i]);
                        if (in_array($value, ['*/', '*'])) {
                            $i--;
                            break;
                        }
                        $value = trim(substr($value, 1));
                        $trArr = explode('|', $value);
                        $tbody = [];
                        foreach ($trArr as $tr) {
                            if ($i == $iTmp) {
                                $thead[] = trim($tr);
                            } else {
                                $tbody[] = trim($tr);
                            }
                        }
                        !empty($tbody) && $tableTmp[] = $tbody;
                    }
                    $table[] = [
                        'title' => $tableTitle,
                        'thead' => $thead,
                        'body' => $tableTmp,
                    ];
                    break;
            }

        }

        return [
            'params' => $params,
            'otherComment' => $otherComment,
            'example' => $example,
            'table' => $table,
            'desc' => $desc
        ];
    }
}
