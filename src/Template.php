<?php
namespace MorsTiin\AnnotatedDoc;

/**
 * 文档模版
 */
class Template
{
    public function show()
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../view/');
        $twig = new \Twig\Environment($loader);
        $config = Config::getInstance();
        $module = new Module();
        $res = $module->getDoc();
        
        return $twig->render('index.html', $res);
    }
}