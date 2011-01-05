<?php
/**
 * Punny - The most easy-to-use PHP MVC framework
 * http://punny.skiyo.cn/
 *
 * Copyright (c) 2009 Jessica(董立强)
 * Licensed under the MIT license.
 *
 * @author dongliqiang<jessica.dlq@gmail.com>
 * @version $Id: Controller.class.php 374 2010-08-03 03:39:07Z jessica.dlq $
 */
class Controller{

	/**
	 * URL参数
	 *
	 * @var array
	 */
	private $param;

	/**
	 * 构造函数.
	 *
	 * @return void
	 * @access public
	 *
	 */
	public function __construct() {
		$this->parsePath();
		$this->getControllerFile();
		$this->getControllerClass();
	}

	/**
	 * 解析URL路径
	 *
	 * @return void
	 * @access private
	 *
	 */
	private function parsePath(){
		global $PunnyConfig;
		strpos($_SERVER['REQUEST_URI'], URL) !== false ?
				$url = substr($_SERVER['REQUEST_URI'], strlen(URL)) :
				$url = substr($_SERVER['REQUEST_URI'], strlen(ROOT));
		$param = explode('/', $url);
		//确定Controller以及Action
		if (empty($param[0]) || strtolower($param[0]) == 'index.php') {
			$this->param['PUNNY_CONTROLLER'] = 'Index';
			$this->param['PUNNY_CONTROLLER_FILE'] = 'IndexAction';
			$this->param['PUNNY_ACTION'] = 'execute';
		} else {
			//Controller的第一个字符必须为字母
			if($this->isLetter($param[0])) {
				//运用Struts2中的Action方式 以$PunnyConfig['Common']['urlseparator']为Controller和Action的分隔符
				//没有$PunnyConfig['Common']['urlseparator']就是使用默认的Action execute方法
				if(strpos($param[0], $PunnyConfig['Common']['urlseparator']) === false) {
					$this->param['PUNNY_CONTROLLER'] = $param[0];
					$this->param['PUNNY_CONTROLLER_FILE'] = $param[0] . 'Action';
					$this->param['PUNNY_ACTION'] = 'execute';
				} else {
					list($this->param['PUNNY_CONTROLLER'], $this->param['PUNNY_ACTION']) = explode($PunnyConfig['Common']['urlseparator'], $param[0]);
					//Action的第一个字母必须为字母
					//如果不是字母就执行默认的Action execute方法
					$this->isLetter($this->param['PUNNY_ACTION']) || $this->param['PUNNY_ACTION'] = 'execute';
					$this->param['PUNNY_CONTROLLER_FILE'] = $this->param['PUNNY_CONTROLLER'] . 'Action';
				}
				//在Url中可以使用"."来作为Controller的目录分隔符
				$this->param['PUNNY_CONTROLLER_FILE'] = str_replace('.', DIRECTORY_SEPARATOR, $this->param['PUNNY_CONTROLLER_FILE']);
			} else {
				throw new PunnyException("错误的Controller请求:[{$param[0]}]");
			}
		}
		//重组参数
		$count = count($param);
		for ($i=1;$i<=$count;$i++) {
			$this->param[@$param[$i]] = @$param[++$i];
		}
	}

	/**
	 * 根据解析的URL获取Controller文件
	 *
	 * @return void
	 * @access private
	 *
	 */
	private function getControllerFile(){
		$controllerFile = PUNNY_ROOT . DIRECTORY_SEPARATOR .'Controller' . DIRECTORY_SEPARATOR . $this->param['PUNNY_CONTROLLER_FILE'] . '.php';
		if(is_file($controllerFile)) {
			require_once($controllerFile);
		} else {
			throw new PunnyException("错误的请求，找不到Controller文件:[$controllerFile]");
		}
	}

	/**
	 * 根据Controller文件名获取Controller类名并且执行
	 *
	 * @return void
	 * @access private
	 *
	 */
	private function getControllerClass(){
		$controllerClass = str_replace(DIRECTORY_SEPARATOR, '',
				($tmp = strrchr($this->param['PUNNY_CONTROLLER_FILE'], DIRECTORY_SEPARATOR)) === false ? $this->param['PUNNY_CONTROLLER_FILE'] : $tmp);
		if(class_exists($controllerClass)) {
			//取的Controller中的所有方法
			$methods = get_class_methods($controllerClass);
			//判断是否存在Action
			if(!in_array($this->param['PUNNY_ACTION'], $methods)) {
				throw new PunnyException("错误的请求，找不到Action:[{$this->param['PUNNY_ACTION']}]");
			}
			new HttpRequest($this->param);
			$action = new $controllerClass();
			//执行初始化Action
			in_array('_init', $methods) && $action->_init();
			$action->{$this->param['PUNNY_ACTION']}();
		} else {
			throw new PunnyException("错误的请求，找不到Controller类:[$controllerClass]");
		}
	}

	/**
	 * 判断第一个字符是否为字母
	 *
	 * @param string $char
	 * @return boolean
	 */
	private function isLetter($char) {
		$ascii = ord($char{0});
		return ($ascii >= 65 && $ascii <= 90) || ($ascii >= 97 && $ascii <= 122);
	}
}