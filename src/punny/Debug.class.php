<?php
/**
 * Punny - The most easy-to-use PHP MVC framework
 * http://punny.skiyo.cn/
 *
 * Copyright (c) 2009 Jessica(董立强)
 * Licensed under the MIT license.
 *
 * @author dongliqiang<jessica.dlq@gmail.com>
 * @version $Id: Debug.class.php 374 2010-08-03 03:39:07Z jessica.dlq $
 */
class Debug {
	
	/**
	 * DEBUG实例
	 *
	 * @var object
	 * @static
	 */
	protected static $instance = null;

	/**
	 * debug信息
	 * 
	 * @var array
	 */
	protected $info = '';

	/**
	 * debug模板
	 * 
	 * @var string
	 */
	protected $debug = '<style type="text/css">#PunnyDebug {padding:0;margin:0;font-family:"微软雅黑", "宋体", Verdana, Geneva, sans-serif;font-size:14px;}.debug_box {width:95%;margin:20px auto;border:#99C4D5 solid 1px}.debug_box .debug_box_top {background:#C5E3E9;font-size:14px;padding:10px;color:#F30}.debug_i {color:#F60}.debug_box .debug_body {background:#ECF7F9;padding:10px;color:#0678A9;border-top:#99C4D5 solid 1px}</style><hr /><div id="PunnyDebug"><div class="debug_box"><div class="debug_box_top">以下为Debug信息</div>%s</div></div>';

	/**
	 * debug每条信息模板
	 * 
	 * @var string
	 */
	protected $div = '<div class="debug_body">[<span class="debug_i">%s</span>] : <br />%s</div>';

	/**
	 * 取得DEBUG实例
	 *
	 * @return object
	 * @access public
	 * @static
	 */
	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new Debug();
		}
		return self::$instance;
	}

	/**
	 * 设置显示变量
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function setVar($key, $value) {
		$this->info .= sprintf($this->div, $key, '<pre>' . var_export($value, true) . '</pre>');
	}

	/**
	 * 设置显示SQL信息
	 * 
	 * @param string $sql
	 * @param bool $success
	 */
	public function setSQL($sql, $success = true, $error = null) {
		$this->info .= sprintf($this->div, '执行SQL语句(' . ($success ? '成功' : ('失败' . $error)) . ')', '<pre>' . $sql . '</pre>');
	}

	/**
	 * 取得最终的debug信息
	 * 
	 * @return string
	 */
	public function getDebug() {
		return sprintf($this->debug, null, $this->info);
	}
}
