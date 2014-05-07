<?php

/**
 * 逻辑区：团购券相关
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package logic
 * @name coupon.logic.php
 * @version 1.0
 */

class CouponLogic
{
	
	public function GetOne($id)
	{
		$sql = '
		SELECT
			t.*, p.name, p.flag, p.intro, p.perioddate
		FROM
			' . table('ticket').' t
		LEFT JOIN
			' . table('product').' p
		ON
			(t.productid = p.id)
		WHERE
			t.ticketid='.$id;
		return dbc()->Query($sql)->GetRow();
	}
	
	public function GetList( $uid = 0, $oid = 0, $status = TICK_STA_Unused, $pid = false )
	{
		$sql_limit_user = '1';
		if ($uid > 0)
		{
			$sql_limit_user = 't.uid = '.$uid;
		}
		$sql_limit_order = '1';
		if ($oid > 0)
		{
			$sql_limit_order = 't.orderid = '.$oid;
		}
		$sql_limit_status = 't.status = '.$status;
		if ($status < 0)
		{
			$sql_limit_status = '1';
		}
		if ($status == TICK_STA_Used) {
			$orderby = 't.usetime';
		}else{
			$orderby = 't.ticketid';
		}
		$sql_limit_product = $pid ? ('t.productid='.$pid) : '1';
		$sql = 'SELECT t.*, p.name, p.flag, p.intro, p.perioddate
		FROM
			' . table('ticket').' t
		LEFT JOIN
			' . table('product').' p
		ON
			(t.productid = p.id)
		WHERE
			'.$sql_limit_user.'
		AND
			'.$sql_limit_order.'
		AND
			' . $sql_limit_status . '
		AND
			'.$sql_limit_product.'
		ORDER BY
			'.$orderby.'
		DESC';
		logic('isearcher')->Linker($sql);
		$sql = page_moyo($sql);
		return dbc(DBCMax)->query($sql)->done();
	}
	
	public function SrcOne($id)
	{
		$result = dbc(DBCMax)->select('ticket')->where('ticketid='.$id)->done();
		return $result[0];
	}
	
	public function SrcList($uid = 0, $oid = 0, $status = TICK_STA_Unused)
	{
		$sql_limit_user = '1';
		if ($uid > 0)
		{
			$sql_limit_user = 'uid = '.$uid;
		}
		$sql_limit_order = '1';
		if ($oid > 0)
		{
			$sql_limit_order = 'orderid = '.$oid;
		}
		$sql_limit_status = 'status = '.$status;
		if ($status < 0)
		{
			$sql_limit_status = '1';
		}
		$sql = 'SELECT *
		FROM
			' . table('ticket').'
		WHERE
			'.$sql_limit_user.'
		AND
			'.$sql_limit_order.'
		AND
			' . $sql_limit_status . '
		ORDER BY
			ticketid
		DESC';
		return dbc(DBCMax)->query($sql)->done();
	}
	
	public function STA_Name($STA_Code)
	{
		$STA_NAME_MAP = array(
			TICK_STA_Unused => '还未使用',
			TICK_STA_Used => '已经使用',
			TICK_STA_Overdue => '已经过期',
			TICK_STA_Invalid => '号码无效'
		);
		return $STA_NAME_MAP[$STA_Code];
	}
	
	public function Create($pid, $oid, $uid, $mutis = 1, $number = false, $password = false, $idx = false)
	{
		$number = $this->__freeNumber($number);
		$password = $password ? $password : $this->__random_num(6);
		$guid = is_numeric($idx) ? ($oid.'-'.$idx) : ('USR-'.md5((string)microtime(true)));
		$data = array
		(
			'uid' => $uid,
			'productid' => $pid,
			'orderid' => $oid,
			'guid' => $guid,
			'number' => $number,
			'password' => $password,
			'mutis' => $mutis,
			'status' => TICK_STA_Unused
		);
		dbc()->SetTable(table('ticket'));
		$id = dbc()->Insert($data);
		if ($id)
		{
			zlog('coupon')->create($id, $data);
			logic('coupon')->Create_OK($uid, $data);
		}
	}
	
	public function __freeNumber($number = false)
	{
		$number = $number ? $number : $this->__random_num(12);
		$exists = dbc(DBCMax)->select('ticket')->where('number='.$number)->limit(1)->done();
		return $exists ? $this->__freeNumber() : $number;
	}
	
	public function Create_OK($uid, $data)
	{
		$data['product'] = logic('product')->GetOne($data['productid']);
		$data['product']['perioddate'] = date('Y-m-d H:i:s', $data['product']['perioddate']);
		logic('notify')->Call($uid, 'logic.coupon.Create', $data);
	}
	
	public function Delete($id)
	{
		zlog('coupon')->delete($id);
		return dbc(DBCMax)->delete('ticket')->where('ticketid='.$id)->done();
	}
	
	public function __random_num($length = 12)
	{
		$length = (int)$length;
		$loops = ceil($length / 3);
		$string = '';
		for ( $i=0; $i<$loops; $i++ )
		{
			$string .= (string)mt_rand(100, 999);
		}
		$string = substr($string, 0, $length);
		return $string;
	}
	
	public function Count($uid = null, $pid = null, $oid = null, $status = null)
	{
		$dbq = dbc(DBCMax)->select('ticket')->in('COUNT(1) as tikCNT');
		is_null($uid) || $dbq->where('uid='.$uid);
		is_null($pid) || $dbq->where('productid='.$pid);
		is_null($oid) || $dbq->where('orderid='.$oid);
		is_null($status) || $dbq->where('status='.$status);
		$r = $dbq->limit(1)->done();
		return $r['tikCNT'] ? $r['tikCNT'] : 0;
	}
	
	public function Summary($pid = null, $status = TICK_STA_Used)
	{
				$product = logic('product')->SrcOne($pid);
		$price = $product['nowprice'];
		$r = dbc(DBCMax)->select('ticket')->in('SUM(mutis*'.$price.') AS moneyALL')->where('productid='.$pid.' AND status='.$status)->limit(1)->done();
		return $r['moneyALL'] ? $r['moneyALL'] : 0;
	}
	
	public function TicketGet($number)
	{
		$coupon = dbc(DBCMax)->select('ticket')->where(array('number' => $number))->limit(1)->done();
		$coupon || $coupon = array();
		if ($coupon && $coupon['status'] == TICK_STA_Unused)
		{
			logic('me')->ticketCheck($coupon);
		}
		return $coupon;
	}
	
	public function ProductGet($number)
	{
		$coupon = $this->TicketGet($number);
		if ($coupon)
		{
			$product = logic('product')->GetOne($coupon['productid']);
			$product['coupon'] = $coupon;
			$product['coupon']['attrs'] = logic('attrs')->snapshot($coupon['orderid'], $coupon['productid']);
			return $product;
		}
		else
		{
			return array();
		}
	}
	
	public function MakeUsed($number, $password, $ops_member_id = MEMBER_ID)
	{
		$product = $this->ProductGet($number);
		if ($product)
		{
			switch ($product['coupon']['status'])
			{
				case TICK_STA_Unused :
					if ($product['sellerid'] != logic('seller')->U2SID($ops_member_id))
					{
						return $this->mu_error('access-denied', $product['coupon']);
					}
					else
					{
						if ($product['coupon']['password'] != $password)
						{
							return $this->mu_error('password-wrong', $product['coupon']);
						}
						else
						{
							$usetime = date('Y-m-d H:i:s', time());
														dbc(DBCMax)->update('ticket')->data(array('status' => TICK_STA_Used, 'usetime' => $usetime))->where(array('ticketid' => $product['coupon']['ticketid']))->done();
														logic('notify')->Call($product['coupon']['uid'], 'logic.coupon.Used', array(
								'productflag' => $product['flag'],
								'number' => $product['coupon']['number'],
								'time' => $usetime
							));
							zlog('coupon')->used($product['coupon']);
														$product['coupon']['flag'] = $product['flag'];
							return $this->mu_success($product['coupon']);
						}
					}
					break;
				case TICK_STA_Used :
					return $this->mu_error('be-used', $product['coupon']);
					break;
				case TICK_STA_Overdue :
					return $this->mu_error('be-overdue', $product['coupon']);
					break;
				case TICK_STA_Invalid :
					return $this->mu_error('be-invalid', $product['coupon']);
					break;
			}
		}
		else
		{
			return $this->mu_error('not-found');
		}
	}
	
	private function mu_error($errcode, $coupon = array())
	{
		return array(
			'error' => true,
			'errcode' => $errcode,
			'coupon' => $coupon
		);
	}
	
	private function mu_success($coupon)
	{
		return array(
			'error' => false,
			'coupon' => $coupon
		);
	}
}
?>