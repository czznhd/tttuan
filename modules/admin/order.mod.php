<?php

/**
 * 模块：订单管理
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name order.mod.php
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
		header('Location: ?mod=order&code=vlist');
	}
	function vList()
	{
		$ordSTA = get('ordsta', 'number');
		is_numeric($ordSTA) || $ordSTA = ORD_STA_ANY;
		$ordPROC = get('ordproc', 'string');
		if ($ordPROC == '__PAY_YET__') {
			$ordPROC = 'pay > 0 and paytime > 0';
		}elseif($ordPROC == 'WAIT_BUYER_PAY'){
			$ordPROC = 'pay = 0 and paytime = 0';
		}else{
			$ordPROC = $ordPROC ? ('process="'.$ordPROC.'"') : '1';
		}
		$list = logic('order')->GetList(0, $ordSTA, ORD_PAID_ANY, $ordPROC);
		$batchURL = str_replace('code=vlist', 'code=batch', page_moyo_request_uri());
		include handler('template')->file('@admin/order_list');
	}
	function Process()
	{
		$referrer = get('referrer', 'txt');
		$id = get('id', 'number');
		$order = logic('order')->GetOne($id);
		if (!$order)
		{
			$this->Messager(__('找不到相关订单！'), '?mod=order');
		}
		$user = user($order['userid'])->get();
		$payment = logic('pay')->SrcOne($order['paytype']);
		$paylog = logic('pay')->GetLog($order['orderid'], $order['userid']);
		$coupons = logic('coupon')->SrcList($order['userid'], $order['orderid'], TICK_STA_ANY);
		$express = logic('express')->SrcOne($order['expresstype']);
		$address = logic('address')->GetOne($order['addressid']);
		$clog = logic('order')->clog($order['orderid'])->vlist();
		include handler('template')->file('@admin/order_process');
	}
	function Batch()
	{
		$searchWhere = get('ssrc') ? ini('isearcher.map.'.get('ssrc').'.name') : '任意';
		$searchValue = get('sstr') ? get('sstr') : '任意';
		$ordSTA = get('ordsta', 'number');
		is_numeric($ordSTA) || $ordSTA = ORD_STA_ANY;
		$searchSTA = logic('order')->STA_Name($ordSTA);
		$ordPROC = get('ordproc', 'string');
		$ordSPROC = $ordPROC ? $ordPROC : '*';
		$ordPROC = $ordPROC ? ('process="'.$ordPROC.'"') : '1';
		$searchPROC = logic('order')->PROC_Name($ordSPROC);
				$_GET[EXPORT_GENEALL_FLAG] = EXPORT_GENEALL_VALUE;
		$_GET['code'] = 'vlist';
				$list = logic('order')->GetList(0, $ordSTA, ORD_PAID_ANY, $ordPROC);
		$allCount = $list ? count($list) : 0;
				$_GET['code'] = 'batch';
				$ccURL = str_replace('code=batch', 'code=batch&op=done', page_moyo_request_uri());
		include handler('template')->file('@admin/order_process_batch');
	}
	function Batch_done()
	{
		$action = get('action');
		in_array($action, array('refund', 'confirm', 'cancel', 'afservice', 'ends', 'delete')) || exit('false');
		$ordSTA = get('ordsta', 'number');
		is_numeric($ordSTA) || $ordSTA = ORD_STA_ANY;
		$ordPROC = get('ordproc', 'string');
		$ordPROC = $ordPROC ? ('process="'.$ordPROC.'"') : '1';
				$_GET[EXPORT_GENEALL_FLAG] = EXPORT_GENEALL_VALUE;
		$_GET['code'] = 'vlist';
				$list = logic('order')->GetList(0, $ordSTA, ORD_PAID_ANY, $ordPROC);
		if ($list)
		{
			foreach ($list as $i => $one)
			{
				$_GET['oid'] = $one['orderid'];
				$this->$action(false);
			}
		}
		exit('ok');
	}
	function Remark()
	{
		$id = get('oid', 'number');
		$text = get('text', 'txt');
		logic('order')->Update($id, array('remark'=>$text));
		exit('ok');
	}
	function Extmsg_reply()
	{
		$id = get('oid', 'number');
		$text = get('text', 'txt');
		logic('order')->Update($id, array('extmsg_reply'=>$text));
		exit('ok');
	}
	function Refund($exit = true)
	{
		$id = get('oid', 'number');
		$remark = '[退款] '.get('mark', 'txt');
		$rfm = get('refundMoney', 'float');
		if (is_numeric($rfm))
		{
			$remark .= '；退款金额：'.$rfm;
		}
		else
		{
			$rfm = null;
		}
		logic('order')->clog($id)->add('refund', $remark);
		logic('order')->Refund($id, $rfm);
		$exit && exit('ok');
	}
	function Confirm($exit = true)
	{
		$id = get('oid', 'number');
		$r = logic('order')->Confirm($id);
		$remark = '[确认付款] '.get('mark', 'txt').$r;
		logic('order')->clog($id)->add('confirm', $remark);
		$exit && exit('ok');
	}
	function Cancel($exit = true)
	{
		$id = get('oid', 'number');
		$remark = '[取消订单] '.get('mark', 'txt');
		$rfm = get('refundMoney', 'float');
		if (is_numeric($rfm))
		{
			$remark .= '；退款金额：'.$rfm;
		}
		else
		{
			$rfm = null;
		}
		logic('order')->clog($id)->add('cancel', $remark);
		logic('order')->Cancel($id, $rfm);
		$exit && exit('ok');
	}
	function AfService($exit = true)
	{
		$id = get('oid', 'number');
		$mark = get('mark', 'txt');
		$remark = '[售后] '.$mark;
		logic('order')->clog($id)->add('afservice', $remark);
		$order = logic('order')->SrcOne($id);
		logic('notify')->Call($order['userid'], 'admin.mod.order.AfService', array('orderid'=>$id,'remark'=>$mark));
		$exit && exit('ok');
	}
	function Ends($exit = true)
	{
		$id = get('oid', 'number');
		$mark = get('mark', 'txt');
		$remark = '[结单] '.$mark;
		logic('order')->clog($id)->add('ends', $remark);
		logic('order')->Update($id, array('process'=>'TRADE_FINISHED'));
		$exit && exit('ok');
	}
	function Delete($exit = true)
	{
		$id = get('oid', 'number');
		logic('order')->Delete($id);
		$exit && exit('ok');
	}
	function Reset($exit = true)
	{
		$id = get('oid', 'number');
		$mark = get('mark', 'txt');
		$remark = '[重启订单] '.$mark;
		logic('order')->clog($id)->add('reset', $remark);
		logic('order')->Update($id, array('status'=>ORD_STA_Normal));
		$exit && exit('ok');
	}
}


?>