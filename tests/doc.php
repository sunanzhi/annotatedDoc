<?php
require_once "../../vendor/autoload.php";

use MorsTiin\AnnotatedDoc\Config;
use MorsTiin\AnnotatedDoc\Template;

$config = Config::getInstance();
$config->moduleList = [
    ['path' => __DIR__.'/example', 'namespace' => 'MorsTiin\\AnnotatedDoc\\example', 'name' => 'example'],
];

echo (new Template())->show();