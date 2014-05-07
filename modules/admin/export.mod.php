<?php

/**
 * 模块：数据导出
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name export.mod.php
 * @version 1.0
 */

class ModuleObject extends MasterObject
{
	function ModuleObject( $config )
	{
		$this->MasterObject($config);
		$runCode = Load::moduleCode($this);
		$this->$runCode();
	}
	function Main()
	{
		exit('Modules.export.index');
	}
	function Order()
	{
		$this->selector('order');
	}
	function Order_generate()
	{
		$format = $this->__set_filter('order');
				$ordSTA = get('ordsta', 'number');
		is_numeric($ordSTA) || $ordSTA = ORD_STA_ANY;
		$ordPROC = get('ordproc', 'string');
		$ordPROC = $ordPROC ? ('process="'.$ordPROC.'"') : '1';
		$list = logic('order')->GetList(0, $ordSTA, ORD_PAID_ANY, $ordPROC);
				include handler('template')->file('@export/order.'.$format);
		$this->doResult('order', $format);
	}
	function Coupon()
	{
		$this->selector('coupon');
	}
	function Coupon_generate()
	{
		$format = $this->__set_filter('coupon');
				$coupSTA = get('coupsta', 'number');
		is_numeric($coupSTA) || $coupSTA = TICK_STA_ANY;
		$list = logic('coupon')->GetList(USR_ANY, ORD_ID_ANY, $coupSTA);
				include handler('template')->file('@export/coupon.'.$format);
		$this->doResult('coupon', $format);
	}
	function Delivery()
	{
		$this->selector('delivery');
	}
	function Delivery_generate()
	{
		$format = $this->__set_filter('delivery');
				$alsend = get('alsend', 'txt');
		$alsend = ($alsend == 'yes') ? DELIV_SEND_Yes : (($alsend == 'no') ? DELIV_SEND_No : DELIV_SEND_OK);
		$list = logic('delivery')->GetList($alsend);
				include handler('template')->file('@export/delivery.'.$format);
		$this->doResult('delivery', $format);
	}
	function Subscribe()
	{
		$this->selector('subscribe');
	}
	function Subscribe_generate()
	{
		$format = $this->__set_filter('subscribe');
				$class = get('class', 'txt');
		$class = $class ? $class : 'mail';
        $list = logic('subscribe')->GetList($class);
				include handler('template')->file('@export/subscribe.'.$format);
		$this->doResult('subscribe', $format);
	}
	private function selector($class)
	{
		$action = $class;
		$filter = $this->__get_filter();
		include handler('template')->file('@admin/export_selector');
	}
	private function doResult($class, $format)
	{
		$export = ob_get_contents();
		$file = $this->__write_cache($class, $format, $export);
		header('Location: ?mod=export&code=result&file='.$file);
		exit;
	}
	public function result()
	{
		$file = get('file');
		$ops = array(
			'name' => $file,
			'url' => ini('settings.site_url').'/cache/export/'.$file
		);
		exit(jsonEncode($ops));
	}
	private function __write_cache($class, $format, $content)
	{
		$dir = CACHE_PATH.'/export/';
		if (!is_dir($dir))
		{
			@mkdir($dir, 0777);
		}
		$file = $class.'_'.date('YmdHis').'.'.$format;
		file_put_contents($dir.$file, ENC_IS_GBK ? $content : ENC_U2G($content));
		return $file;
	}
	private function __get_filter()
	{
		$url = urldecode(get('referrer'));
				$params = explode('&', $url);
		$_PARMS = array();
		foreach ($params as $query)
		{
			list($key, $val) = explode('=', $query);
			if ($key == 'mod' || $key == 'code')
			{
				continue;
			}
			$_PARMS[$key] = $val;
		}
		$filter = base64_encode(serialize($_PARMS));
		return $filter;
	}
	private function __set_filter($class)
	{
		$geneall = get('geneall', 'txt');
		$filter = unserialize(base64_decode(get('filter')));
		$_GET = array_merge($_GET, $filter);
				$_GET['mod'] = $class;
		$_GET['code'] = 'vlist';
		if ($geneall == 'yes')
		{
						$_GET[EXPORT_GENEALL_FLAG] = EXPORT_GENEALL_VALUE;
		}
		return get('format', 'txt');
	}
}


?>