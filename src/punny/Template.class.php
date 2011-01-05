<?php
/**
 * Punny - The most easy-to-use PHP MVC framework
 * http://punny.skiyo.cn/
 *
 * Copyright (c) 2009 Jessica(董立强)
 * Licensed under the MIT license.
 *
 * @author dongliqiang<jessica.dlq@gmail.com>
 * @version $Id: Template.class.php 374 2010-08-03 03:39:07Z jessica.dlq $
 */
class Template {
	/**
	 * 模板后缀名
	 *
	 * @var string
	 */
	private $suffix = '.php';
	/**
	 * 模板变量
	 *
	 * @var array
	 */
	private $value;
	/**
	 * 模板引擎实例
	 *
	 * @var object
	 */
	private static $instance = null;

	/**
	 * 取得模板引擎的实例
	 *
	 * @return objeact
	 * @access public
	 * @static
	 */
	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new Template();
		}
		return self::$instance;
	}
	/**
	 * 打印模板
	 *
	 * @param string $file 模板名
	 */
	public function show($file) {
		//模板文件名
		$file = str_replace('.', DIRECTORY_SEPARATOR, $file) . $this->suffix;
		//模板目录
		$templateDir = PUNNY_ROOT . 'View' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR;
		//模板缓存目录
		$cacheTemplateDir = PUNNY_ROOT . 'Cache' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR;
		//模板文件目录
		$tplFile = $templateDir . $file;
		//缓存文件名
		$cacheFile = $cacheTemplateDir . urlencode($file) . '.php';

		if(is_file($tplFile)) {
			if(is_file($cacheFile) && filemtime($tplFile) == filemtime($cacheFile)) {
				@extract($this->value);
				include($cacheFile);
			} else {
				//写入缓存
				file_put_contents($cacheFile, $this->compile($tplFile));
				touch($cacheFile, filemtime($tplFile));
				@extract($this->value);
				include($cacheFile);
			}
		} else {
			throw new PunnyException('找不到模板文件:' . $tplFile);
		}
	}

	/**
	 * 编译模板文件为缓存文件
	 *
	 * @param string $file 模板文件路径
	 * @return string      生成的缓存数据
	 */
	protected function compile($file) {
		$tpl = file_get_contents($file);

		//替换VIEW URL ROOT标签
		$searchArray = array('<VIEW>', '<URL>', '<ROOT>');
		$replaceArray = array(VIEW, URL, ROOT);
		$tpl = str_replace($searchArray, $replaceArray, $tpl);

		//替换include
		$tpl = preg_replace('/<include>(.*?)<\/include>/i', '<? $this->show("\\1"); ?>', $tpl);
		//替换url
		$tpl = preg_replace_callback('/<url>(.*?)<\/url>/i', array($this, 'bulidUrl'), $tpl);
		//替换view
		$tpl = preg_replace('/<view>(.*?)<\/view>/ie', 'Punny::file("View/\\1")', $tpl);
		//替换file
		$tpl = preg_replace('/<file>(.*?)<\/file>/ie', 'Punny::file("\\1")', $tpl);
		
		return $tpl;
	}

	/**
	 * 构造<url></url>的正则callback方法
	 * 由于callback不能使用protected以下的方法 所以必须设定为public的
	 * 
	 * @param array $matches
	 * @return string
	 */
	public function bulidUrl($matches) {
		if(!empty($matches[1])) {
			$url = explode(',', $matches[1]);
			foreach($url as &$v) {
				$trim = trim($v, " '");
				if(strcmp($trim, 'true') == 0 || strcmp($trim, 'false') == 0) {
					$v = (bool)$trim;
				} else {
					$v = $trim;
				}
			}
			$url = Punny::url($url);
		} else {
			$url = Punny::url();
		}
		return $url;
	}

	/**
	 * 分配变量
	 *
	 * @param string $key 模板变量名
	 * @param mixed $value 模板变量的值
	 * @return void
	 */
	public function assign($key, $value){
		$this->value[$key] = $value;
	}

	/**
	 * 通过数组分配变量
	 *
	 * @param array $array
	 */
	public function assignByArray($array) {
		if (is_array($array)) {
			foreach ($array as $k => $v) {
				$this->value[$k] = $v;
			}
		}
	}

	/**
	 * 获取已经分配的模板值
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getValue($key) {
		return isset($this->value[$key]) ? $this->value[$key] : null;
	}

	/**
	 * 设置模板后缀名
	 *
	 * @param string $suffix 后缀名
	 * @return void
	 */
	public function setSuffix($suffix = '.html') {
		$this->suffix = ($suffix{0} == '.' ? $suffix : '.' . $suffix);
	}

}