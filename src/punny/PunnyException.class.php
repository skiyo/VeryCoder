<?php
/**
 * Punny - The most easy-to-use PHP MVC framework
 * http://punny.skiyo.cn/
 *
 * Copyright (c) 2009 Jessica(董立强)
 * Licensed under the MIT license.
 *
 * @author dongliqiang<jessica.dlq@gmail.com>
 * @version $Id: PunnyException.class.php 374 2010-08-03 03:39:07Z jessica.dlq $
 */
class PunnyException extends Exception {
	/**
	 * 优化异常页面
	 * 
	 * @var string
	 */
    private $html = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Punny罢工啦!!</title><style type="text/css">*{padding:0;margin:0;font-family:"微软雅黑", "宋体", Verdana, Geneva, sans-serif;font-size:12px;background-color:#EAF8F9}.box{width:600px;margin:20px auto;border:#99C4D5 solid 1px}.box .top{background:#C5E3E9;font-size:14px;padding:10px;color:#F30}.improtant{ color:#F60}.box .body{background:#ECF7F9;padding:10px;color:#0678A9}#footer{width:600px;margin:30px auto}#footer .myhr{background-color:#99C4D5;height:3px}#footer .body{padding:5px 10px;text-align:center;color:#4E4E4E;font-size:11px}</style></head><body><div class="box"><div class="top">Punny罢工啦：%s</div><div class="body">在[<span class="improtant">%s</span>]的第[<span class="improtant">%s</span>]行. <br />%s</div></div><div id="footer"><div class="myhr"></div><div class="body">Powered by Punny.</div></div></body></html>';

	/**
	 * 构造器
	 * 
	 * @param string $message
	 * @param int $code
	 * @access public
	 */
    public function __construct($message = 'Unknown Error', $code = 0) {
        parent::__construct($message, $code);
    }

	/**
	 * 输出异常信息
	 *
	 * @return void
	 * @access public
	 */
    public function getError() {
        die(sprintf($this->html, urldecode($this->getMessage()), $this->getFile(), $this->getLine(), $this->getTraceAsString()));
    }
}