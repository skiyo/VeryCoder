<?php
/**
 * Punny - The most easy-to-use PHP MVC framework
 * http://punny.skiyo.cn/
 *
 * Copyright (c) 2009 Jessica(董立强)
 * Licensed under the MIT license.
 *
 * @author dongliqiang<jessica.dlq@gmail.com>
 * @version $Id: Log.class.php 375 2010-08-03 06:51:23Z jessica.dlq $
 */
class Log {

	protected $path;

	public function  __construct() {
		$this->path = PUNNY_ROOT . 'Log';
	}

	public static function notice($message) {
		
	}

	public static function warning($message) {
		
	}

	public static function fatal($message) {
		
	}

}