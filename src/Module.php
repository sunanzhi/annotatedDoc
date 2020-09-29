<?php
namespace MorsTiin\AnnotatedDoc;

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
        $requestUrl = '';
        $methodDoc = [];
        $classList = [];
        // 遍历模块
        foreach ($config->moduleList as $module) {
            // 有效模块
            $availableModule[] = $module['name'];
            if ($module['name'] == $config->defaultModule) {
                // 遍历类
                $classFileList = scandir("{$module['path']}");
                foreach ($classFileList as $classFileName) {
                    if (in_array($classFileName, $config->forbiddenLevel) || is_dir($classFileName)) {
                        continue;
                    }
                    // 如果非php文件则忽略
                    if(strpos($classFileName, '.php') === false) {
                        continue;
                    }
                    $className = substr($classFileName, 0, -4);

                    $class = new \ReflectionClass("{$module['namespace']}\\{$className}");
                    if ($class->isInstance($class)) {
                        continue;
                    }
                    // 当前文档说明
                    $classDoc = AnnotationDoc::handleClassComment($class);
                    // 获取全部公开方法文档
                    $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
                    $docList = [];
                    foreach ($methods as $method) {
                        // 过滤父类方法
                        if (in_array($method->name, ['__construct']) || $method->class != "{$module['namespace']}\\{$className}") {
                            continue;
                        }
                        if(empty($method->getReturnType())){
                            continue;
                        }
                        $methodSummary = AnnotationDoc::handleMethodSummary($method);
                        if ($method->name == $config->defaultMethod && $config->defaultClass == $class->getShortName()) {
                            $requestUrl = '/' . strtolower($module['name']) . '/' . $config->defaultClass . '/' . $config->defaultMethod;
                            $methodDoc = (new AnnotationDoc())->handleMethodComment($method);
                        }
                        // 处理文档
                        $docList[] = [
                            'method' => $method->name,
                            'methodSummary' => $methodSummary
                        ];
                    }
                    $classList[] = [
                        'module' => $module['name'],
                        'className' => $className,
                        'docList' => $docList,
                        'classDoc' => isset($classDoc['summary']) ? $classDoc['summary'] : ''
                    ];
                }
            }
        }
        // 处理requestUrl
        $requestUrl = call_user_func(function() use ($requestUrl, $methodDoc, $config){
            if(empty($methodDoc['requestUrl'])) {
                // 方法没有设置请求url
                if($config->requestUrlCallback === null) {
                    return $requestUrl;
                } else {
                    return $config->handleRequestUrl($requestUrl ?? '', $config->requestUrlCallback);
                }
            } else {
                return $methodDoc['requestUrl'];
            }
        });
        
        return [
            'config' => $config, // 配置文件
            'classList' => $classList, // 类列表
            'requestUrl' => $requestUrl, // 请求url
            'availableModule' => $availableModule, // 可用模块
            'methodDoc' => $methodDoc, // 当前方法文档 
        ];
    }
}
