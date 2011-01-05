<?php
/**
 * Punny - The most easy-to-use PHP MVC framework
 * http://punny.skiyo.cn/
 *
 * Copyright (c) 2009 Jessica(董立强)
 * Licensed under the MIT license.
 *
 * @author dongliqiang<jessica.dlq@gmail.com>
 * @version $Id: Model.class.php 375 2010-08-03 06:51:23Z jessica.dlq $
 */
class  Model extends Base {

	/**
	 * 构造函数 初始化
	 */
	public final function  __construct() {
		parent::__construct();
	}

	/**
	 * 获取SQL执行次数
	 *
	 * @return int
	 */
	public final function getQueryNum() {
		return $this->db->getQueryNum();
	}
}