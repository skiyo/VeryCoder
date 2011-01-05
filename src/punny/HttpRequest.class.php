<?php
/**
 * Punny - The most easy-to-use PHP MVC framework
 * http://punny.skiyo.cn/
 *
 * Copyright (c) 2009 Jessica(董立强)
 * Licensed under the MIT license.
 *
 * @author dongliqiang<jessica.dlq@gmail.com>
 * @version $Id: HttpRequest.class.php 374 2010-08-03 03:39:07Z jessica.dlq $
 */
class HttpRequest {

	/**
	 * 设置参数并进行过滤
	 *
	 * @param array $request
	 */
	public function __construct($request) {
		global $PunnyConfig;
		if($PunnyConfig['Common']['autofilter']) {
			$this->filter($request);
			if (!get_magic_quotes_gpc()) {
				$this->filter($_POST);
				$this->filter($_COOKIE);
				$this->filter($_FILES);
			}
		}
		$_GET = $this->cleanArray($request);
        unset($request);
		unset($_ENV);
		unset($HTTP_ENV_VARS);
		unset($_REQUEST);
		unset($HTTP_POST_VARS);
		unset($HTTP_GET_VARS);
		unset($HTTP_POST_FILES);
		unset($HTTP_COOKIE_VARS);
	}
	/**
	 * 转义
	 *
	 * @param array $array 要过滤的数组
	 * @access protected
	 */
	protected function filter(&$array) {
		if (is_array($array)) {
			foreach ($array as $key => $value) {
				is_array($value) ? $this->filter($value) : $array[$key] = addslashes($value);
			}
		}
	}

	/**
	 * 清理数组
	 *
	 * @param array $array
	 * @return array
	 */
	protected function cleanArray($array) {
		foreach ($array as $key => $value) {
           if (empty($key) && empty($value)) {
			   unset($array[$key]);
		   }
       }
       return $array;
	}
}