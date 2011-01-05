<?php
/**
 * Punny - The most easy-to-use PHP MVC framework
 * http://punny.skiyo.cn/
 *
 * Copyright (c) 2009 Jessica(董立强)
 * Licensed under the MIT license.
 *
 * @author dongliqiang<jessica.dlq@gmail.com>
 * @version $Id: HttpResponse.class.php 374 2010-08-03 03:39:07Z jessica.dlq $
 */
class HttpResponse {

	/**
	 * 设置一个cookie
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @param int $expire
	 * @return boolean
	 */
	public static function setCookie($key, $value, $expire = 0) {
		global $PunnyConfig;
		if($PunnyConfig['Common']['cookiecrypt']) {
			$key = $PunnyConfig['Common']['cookiepre'] . md5($PunnyConfig['Common']['cookiepre'] . $key);
			$value = base64_encode(serialize($value));
		} else {
			$key = $PunnyConfig['Common']['cookiepre'] . $key;
		}
		return setcookie($key, $value, $expire, ROOT);
	}

	/**
	 * 获取一个cookie
	 * 
	 * @param string $key
	 * @return mixed
	 */
	public static function getCookie($key) {
		global $PunnyConfig;
		if($PunnyConfig['Common']['cookiecrypt']) {
			$key = $PunnyConfig['Common']['cookiepre'] . md5($PunnyConfig['Common']['cookiepre'] . $key);
			return isset($_COOKIE[$key]) ? unserialize(base64_decode($_COOKIE[$key])) : null;
		} else {
			$key = $PunnyConfig['Common']['cookiepre'] . $key;
			return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
		}
	}

	/**
	 * 删除一个cookie
	 *
	 * @global array $PunnyConfig
	 * @param string $key
	 * @return bool
	 */
	public static function delCookie($key) {
		global $PunnyConfig;
		if($PunnyConfig['Common']['cookiecrypt']) {
			$key = $PunnyConfig['Common']['cookiepre'] . md5($PunnyConfig['Common']['cookiepre'] . $key);
		} else {
			$key = $PunnyConfig['Common']['cookiepre'] . $key;
		}
		return setcookie($key, '', -1, ROOT);
	}

	/**
	 * 发送一个header信息
	 * 
	 * @param string $key
	 * @param string $value
	 */
	public static function addHeader($key, $value) {
        if(!headers_sent()) {
			$key = ucfirst(strtolower($key));
			header($key . ': ' . $value);
        } else {
			throw new PunnyException('Header already sent.');
		}
	}

	/**
	 * JS跳转 不受header限制
	 *
	 * @param string $url      跳转到的URL
	 * @param string $errStr   alert提示信息
	 */
	public static function redirect($url = 'BACK', $errStr = 'NOERR', $parent = false) {
		$first = '<script type="text/javascript">';
		$center = '';
		$last = '</script>';
		($errStr == 'NOERR') ? ($center = '') : ($center = 'alert("' . $errStr . '");');
		($url == 'BACK') ? ($center .= 'window.history.go(-1);') : ($center .= ($parent == true ? 'parent.' : '') . 'location.href="' . $url . '";');
		echo $first, $center, $last;
		exit ();
	}
}