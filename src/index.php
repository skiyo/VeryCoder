<?php
define ('VC_ROOT', dirname(__FILE__) . '/');
ini_set('include_path',
	ini_get('include_path') . ':' . 
	VC_ROOT . 'punny/:' .
	VC_ROOT . 'model/:' .
	VC_ROOT . 'action/:'
);

/**
 * 自动加载类
 *
 * @param string $class
 */
function __autoload($class) {
	require_once($class . '.class.php');
}

//开启网站进程
new Punny();