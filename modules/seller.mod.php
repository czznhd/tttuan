<?php

/**
 * 模块：商家后台
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name seller.mod.php
 * @version 1.0
 */

class ModuleObject extends MasterObject
{
	private $uid = 0;
	private $sid = 0;
	
	private function iniz()
	{
		$this->uid = user()->get('id');
		if ($this->uid < 0)
		{
			$this->Messager('请先登录！', '?mod=account&code=login');
		}
		$this->sid = logic('seller')->U2SID($this->uid);
		if ($this->sid < 0)
		{
			$this->Messager('管理员还没有添加您的商家信息，您暂时无法查看商家后台！', 0);
		}
	}
	function ModuleObject( $config )
	{
		$this->MasterObject($config);
		$this->iniz();
		$runCode = Load::moduleCode($this);
		$this->$runCode();
	}
	public function main()
	{
		header('Location: '.rewrite('?mod=seller&code=product&op=list'));
	}
	
	public function product_list()
	{
		$products = logic('product')->GetList(-1, null, 'p.sellerid='.$this->sid);
		logic('seller')->AVParser($products);
		include handler('template')->file('seller_product_list');
	}
	
	public function product_ticket()
	{
		$pid = get('pid', 'int');
				$status = get('status')!==false ? get('status', 'int') : TICK_STA_ANY;
		$fLinkBase = rewrite('?mod=seller&code=product&op=ticket&pid='.$pid.'&status=');
		$fLink = array(
			'all' => array('link'=>$fLinkBase.TICK_STA_ANY,'current'=>''),
			'used' => array('link'=>$fLinkBase.TICK_STA_Used,'current'=>''),
			'unused' => array('link'=>$fLinkBase.TICK_STA_Unused,'current'=>'')
		);
		abs($status) > 1 && $status = TICK_STA_ANY;
		$status == TICK_STA_ANY && $fLink['all']['current'] = ' class="filter_current"';
		$status == TICK_STA_Used && $fLink['used']['current'] = ' class="filter_current"';
		$status == TICK_STA_Unused && $fLink['unused']['current'] = ' class="filter_current"';
				$product = logic('product')->SrcOne($pid);
		if ($product['sellerid'] != $this->sid)
		{
			$this->Messager('这个产品是其他商家的，您无法查看！', '?mod=seller&code=product&op=list');
		}
		$tickets = logic('coupon')->GetList(USR_ANY, ORD_ID_ANY, $status, $pid);
		include handler('template')->file('seller_product_ticket');
	}
	
	public function product_order()
	{
		$pid = get('pid', 'int');
				$status = get('status') !== false ? get('status', 'int') : ORD_PAID_ANY;
		$fLinkBase = rewrite('?mod=seller&code=product&op=order&pid='.$pid.'&status=');
		$fLink = array(
			'all' => array('link'=>$fLinkBase.ORD_PAID_ANY,'current'=>''),
			'used' => array('link'=>$fLinkBase.ORD_PAID_Yes,'current'=>''),
			'unused' => array('link'=>$fLinkBase.ORD_PAID_No,'current'=>'')
		);
		abs($status) > 1 && $status = ORD_PAID_ANY;
		$status == ORD_PAID_ANY && $fLink['all']['current'] = ' class="filter_current"';
		$status == ORD_PAID_Yes && $fLink['used']['current'] = ' class="filter_current"';
		$status == ORD_PAID_No && $fLink['unused']['current'] = ' class="filter_current"';
				$product = logic('product')->SrcOne($pid);
		if ($product['sellerid'] != $this->sid)
		{
			$this->Messager('这个产品是其他商家的，您无法查看！', '?mod=seller&code=product&op=list');
		}
		$orders = logic('order')->GetList(USR_ANY, ORD_STA_ANY, $status, 'o.productid='.$pid);
		include handler('template')->file('seller_product_order');
	}
	
	public function product_delivery()
	{
		$pid = get('pid', 'int');
				$status = get('status') !== false ? get('status', 'int') : DELIV_SEND_No;
		$fLinkBase = rewrite('?mod=seller&code=product&op=delivery&pid='.$pid.'&status=');
		$fLink = array(
			'all' => array('link'=>$fLinkBase.DELIV_SEND_No,'current'=>''),
			'used' => array('link'=>$fLinkBase.DELIV_SEND_Yes,'current'=>''),
			'unused' => array('link'=>$fLinkBase.DELIV_SEND_OK,'current'=>'')
		);
		$status == DELIV_SEND_No && $fLink['all']['current'] = ' class="filter_current"';
		$status == DELIV_SEND_Yes && $fLink['used']['current'] = ' class="filter_current"';
		$status == DELIV_SEND_OK && $fLink['unused']['current'] = ' class="filter_current"';
				$product = logic('product')->SrcOne($pid);
		if ($product['sellerid'] != $this->sid)
		{
			$this->Messager('这个产品是其他商家的，您无法查看！', '?mod=seller&code=product&op=list');
		}
		$deliveries = logic('delivery')->GetList($status, 'p.id='.$pid);
		include handler('template')->file('seller_product_delivery');
	}
	
	public function delivery_single()
	{
		$order = logic('order')->SrcOne(get('oid', 'number'));
		if ($order)
		{
			$product = logic('product')->SrcOne($order['productid']);
			if ($product['sellerid'] == $this->sid)
			{
				logic('delivery')->Invoice(get('oid', 'number'), get('no', 'txt')) && exit('ok');
			}
		}
		exit('error');
	}
	
	public function ajax_alert()
	{
		$id = get('id', 'int');
		$c = logic('coupon')->GetOne($id);
		logic('notify')->Call($c['uid'], 'admin.mod.coupon.Alert', $c);
		exit('ok');
	}
}

?>