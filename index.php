<?php
define ('VC_SRC_ROOT', dirname(__FILE__) . '/src/');
ini_set('include_path',
	ini_get('include_path') . ':' .
	VC_SRC_ROOT . 'punny/:' .
	VC_SRC_ROOT . 'model/:' .
	VC_SRC_ROOT . 'action/:' .
    VC_SRC_ROOT . 'config/:' .
    VC_SRC_ROOT . 'libs/geshi/:' .
    VC_SRC_ROOT . 'libs/smarty/:'
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