<?php
namespace MorsTiin\Core;

/**
 * 多模块应用
 */
class Module
{
    public function __construct()
    {
        $config = Config::getInstance();
        if(!empty($_GET['module'])){
            $config->defaultModule = $_GET['module'];
        }
        if(!empty($_GET['class'])){
            $config->defaultClass = $_GET['class'];
        }
        if(!empty($_GET['method'])){
            $config->defaultMethod = $_GET['method'];
        }
    }

    /**
     * 获取文档
     *
     * @return array
     */
    public function getDoc(): array
    {
        $config = Config::getInstance();
        // 遍历模块
        foreach ($config->moduleList as $module) {
            // 有效模块
            $resModule[] = $module['name'];
            if ($module['name'] == $config->defaultModule) {
                // 遍历类
                $classList = scandir("{$module['path']}");
                foreach ($classList as $classFile) {
                    if (in_array($classFile, $config->forbiddenLevel)) {
                        continue;
                    }
                    $className = substr($classFile, 0, -4);

                    $class = new \ReflectionClass("{$module['namespace']}\\{$className}");
                    if ($class->isInstance($class)) {
                        continue;
                    }
                    // 当前文档说明
                    $classDoc = explode("\n", $class->getDocComment());
                    // 获取全部公开方法文档
                    $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
                    $docList = [];
                    foreach ($methods as $method) {
                        if (in_array($method->name, ['__construct'])) {
                            continue;
                        }
                        if ($method->name == $config->defaultMethod) {
                            $requestUrl = '/' . strtolower($module['name']) . '/' . $config->defaultClass . '/' . $config->defaultMethod;
                            $methodDoc = explode("\n", $class->getMethod($method->name)->getDocComment());
                            $currentDoc = AnnotationDoc::handleComment($methodDoc, $method);
                        }
                        // 处理文档
                        $docList[] = [
                            'method' => $method->name,
                        ];
                    }
                    $resClassList[] = [
                        'module' => $module['name'],
                        'className' => $className,
                        'docList' => $docList,
                    ];
                }
            }

        }
        return [
            'resClassList' => $resClassList,
            'requestUrl' => $requestUrl,
            'resModule' => $resModule,
            'currentDoc' => $currentDoc,
            'defaultModule' => $config->defaultModule,
            'defaultClass' => $config->defaultClass,
            'defaultMethod' => $config->defaultMethod,
            'staticUrl' => $config->staticUrl,
        ];
    }
}
