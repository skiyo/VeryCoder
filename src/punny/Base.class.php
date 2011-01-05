<?php
/**
 * Punny - The most easy-to-use PHP MVC framework
 * http://punny.skiyo.cn/
 *
 * Copyright (c) 2009 Jessica(董立强)
 * Licensed under the MIT license.
 *
 * @author dongliqiang<jessica.dlq@gmail.com>
 * @version $Id: Base.class.php 375 2010-08-03 06:51:23Z jessica.dlq $
 */
class Base {
	
	/**
	 * 模板对象
	 *
	 * @var Template
	 */
	private $tpl;

	/**
	 * 当前时间
	 *
	 * @var int
	 */
	protected $now;

	/**
	 * DB对象
	 *
	 * @var objeact
	 */
	protected $db = null;

	/**
	 * 本次请求是否为POST
	 *
	 * @var bool
	 */
	protected $isPost = false;

	/**
	 * 构造函数 初始化
	 *
	 * @access public
	 */
	public function __construct() {
		$this->isPost = ($_SERVER['REQUEST_METHOD'] === 'POST');
		$this->tpl = Template::getInstance();
		$this->now = time();
	}

	/**
	 * 连接数据库
	 *
	 * @access public
	 * @final
	 */
	public final function connect() {
		$this->db = Punny::getDB();
	}

	/**
	 * 设置模板变量
	 *
	 * @param string $key   模板页面变量
	 * @param mixed $value  对应程序中的变量
	 * @access public
	 * @return void
	 */
	public final function assign() {
		$key = @func_get_arg(0);
		$value = @func_get_arg(1);
		if (is_array($key)) {
			$this->tpl->assignByArray($key);
		} else {
			$this->tpl->assign($key, $value);
		}
		return $this;
	}

	/**
	 * 获取模板的值
	 *
	 * @param string $key
	 * @return mixed
	 */
	public final function value($key) {
		return $this->tpl->getValue($key);
	}

	/**
	 * 显示模板
	 *
	 * @param string $tpl  模板文件名 为空时是 CONTROLLER_ACTION
	 * @access public
	 * @return void
	 */
	public final function V($tpl = null) {
		Punny::view($tpl);
	}

	/**
	 * 获取Model对象
	 *
	 * @param string $model
	 * @param string $ext
	 * @return object
	 */
	public final function M($model, $ext = '.class.php') {
		return Punny::model($model, $ext);
	}

	/**
	 * 输出一个Debug变量信息
	 * 
	 * @global array $PunnyConfig
	 * @param string $key
	 * @param mixed $value
	 */
	public final function D($key, $value) {
		global $PunnyConfig;
		if($PunnyConfig['Common']['debug']) {
			$debug = Debug::getInstance();
			$debug->setVar($key, $value);
		}
	}
}
