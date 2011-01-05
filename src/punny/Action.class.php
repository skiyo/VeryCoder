<?php
/**
 * Punny - The most easy-to-use PHP MVC framework
 * http://punny.skiyo.cn/
 *
 * Copyright (c) 2009 Jessica(董立强)
 * Licensed under the MIT license.
 *
 * @author dongliqiang<jessica.dlq@gmail.com>
 * @version $Id: Action.class.php 374 2010-08-03 03:39:07Z jessica.dlq $
 */
class Action extends Base {

	/**
	 * 构造函数 初始化
	 */
	public final function __construct() {
		parent::__construct();
	}

	/**
	 * 获取脚本执行时间
	 *
	 * @return float
	 * @access public
	 */
	public final function getActionTime() {
		global $PUNNY_START_TIME;
		return microtime(true) - $PUNNY_START_TIME;
	}

	/**
	 * 析构函数
	 * 
	 * @global array $PunnyConfig
	 */
	public final function  __destruct() {
		global $PunnyConfig;
		//打印debug信息
		if($PunnyConfig['Common']['debug']) {
			$debug = Debug::getInstance();
			echo $debug->getDebug();
		}
	}

}