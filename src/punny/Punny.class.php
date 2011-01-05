<?php
/**
 * Punny - The most easy-to-use PHP MVC framework
 * http://punny.skiyo.cn/
 *
 * Copyright (c) 2009 Jessica(董立强)
 * Licensed under the MIT license.
 *
 * @author dongliqiang<jessica.dlq@gmail.com>
 * @version $Id: Punny.class.php 375 2010-08-03 06:51:23Z jessica.dlq $
 */
class Punny {

	/**
	 * Model的instance数组
	 * 
	 * @var array
	 */
	protected static $model = array();

	/**
	 * core
	 */
	public function __construct() {

		global $PunnyConfig;
		//设置编码
		header ( "Content-type: text/html;charset=UTF-8" );

		//设置时区
		@date_default_timezone_set($PunnyConfig['Common']['timezone']);

		//不进行魔术过滤
		set_magic_quotes_runtime(0);

		//开启页面压缩
		function_exists('ob_gzhandler') ? ob_start('ob_gzhandler') : ob_start();

		//页面报错
		error_reporting(E_ALL);

		//控制异常
		set_exception_handler(array($this, 'exception'));
		
		session_start();

		//Router
		new Controller();
	}

	/**
	 * 构造URL
	 *
	 * @param mixed URL的Controller与Action
	 *               如果第一个参数为字符串 就为对应的Controller
	 *               如果第一个参数为true 那么就是本次的Controller
	 *               第二个参数为Action 同理Controller
	 *               如果第三个参数为true 就是保留当前URL的GET参数
	 *               如果第三个参数为字符串 就为设置的URL参数 一个参数一个值 成对出现
	 * @example Punny::url(true);  返回当前Controller的URL
	 *			 Punny::url(true, true);  返回当前Controller与Action的URL
	 *           Punny::url(true, true, true); 返回当前Controller与Action并保留当前$_GET参数的URL
	 *           Punny::url('Test', 'abc', 'key', 'value'); 构造Controller为Test Action为abc的URL
	 *														并且在这个Action中$_GET['key'] = 'value'
	 * @return string 构造成功的URL
	 * @access public
	 * @static
	 */
	public final static function url() {
		//没有参数直接返回根目录
		if(func_num_args() < 1) {
			return ROOT;
		}
		global $PunnyConfig;
		is_array(func_get_arg(0)) ? $args = func_get_arg(0) : $args = func_get_args();
		$url = URL;
		//Controller
		if(isset($args[0]) && is_bool($args[0]) && $args[0] == true) {
			$url .= $_GET['PUNNY_CONTROLLER'];
		} else {
			$url .= $args[0];
		}
		array_shift($args);
		//Action
		if(isset($args[0])) {
			$url .= $PunnyConfig['Common']['urlseparator'];
			if(is_bool($args[0]) && $args[0]) {
				$url .= $_GET['PUNNY_ACTION'] . '/';
			} else {
				$url .= $args[0] . '/';
			}
		}
		array_shift($args);
		//保存URL
		if(isset($args[0])) {
			$urlArr = array();
			if(is_bool($args[0]) && $args[0]) {
				if(count($_GET) > 3) {
					foreach ($_GET as $k => $v) {
						if ($k != 'PUNNY_CONTROLLER' && $k != 'PUNNY_ACTION' && $k != 'PUNNY_CONTROLLER_FILE') {
							$urlArr[$k] = $v;
						}
					}
				}
				array_shift($args);
			}
			$count = count($args);
			if($count > 0) {
				for($i = 0; $i < $count; $i++) {
					//使用键值可以避免多个相同的参数
					$urlArr[$args[$i]] = @$args[++$i];
				}
			}
			//重组参数
			foreach($urlArr as $k => $v) {
				$url .= "$k/$v/";
			}
		}
		return $url;
	}

	/**
	 * 获取缓存实例
	 *
	 * @return object
	 * @access public
	 * @static
	 */
	public final static function getCache() {
		return CacheDriver::getInstance();
	}

	/**
	 * 获取数据库连接实例
	 *
	 * @return objeact
	 * @access public
	 * @static
	 */
	public final static function getDB() {
		return DatabaseDriver::getInstance();
	}

	/**
	 * 获取模板引擎实例
	 *
	 * @return object
	 * @access public
	 * @static
	 */
	public final static function getTemplate() {
		return Template::getInstance();
	}

	/**
	 * 控制异常的回调函数
	 * 回调函数必须为public
	 * 
	 * @param object $e
	 * @access public
	 */
	public final function exception($e) {
		$e->getError();
	}

}