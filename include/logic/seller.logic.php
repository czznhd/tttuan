<?php

/**
 * 逻辑区：商家相关
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package logic
 * @name seller.logic.php
 * @version 1.0
 */

class SellerLogic
{
	
	public function GetOne($sid = null, $uid = null, $city = null)
	{
		$sql_filter = '1';
		is_null($sid) || $sql_filter .= ' AND id='.$sid;
		is_null($uid) || $sql_filter .= ' AND userid='.$uid;
		is_null($city) || $sql_filter .= ' AND area='.$city;
		$sql = 'SELECT * FROM '.table('seller').' WHERE '.$sql_filter.' LIMIT 1';
		return dbc()->Query($sql)->GetRow();
	}
	
	public function Add($city, $userid, $sellername = '合作商家', $sellerphone = '', $selleraddress = '', $sellerurl = '', $map = '')
	{
		$data = array(
			'userid' => intval($userid),
			'sellername' => $sellername,
			'sellerphone' => $sellerphone,
			'selleraddress' => $selleraddress,
			'sellerurl' => $sellerurl,
			'sellermap' => $map,
			'area' => $city,
			'time'=> time()
		);
		dbc()->SetTable(table('seller'));
		return dbc()->Insert($data);
	}
	
	public function Register($username, $password)
	{
				$userExists = account()->Exists('username', $username);
		if ($userExists)
		{
			$user = account()->Search('username', $username, 1);
						if ($user['role_id'] != 6)
			{
				return array('error' => true, 'result' => '此用户不是合作商家身份，请换一个！');
			}
						$seller = logic('seller')->GetOne(null, $user['id']);
			if ($seller)
			{
				return array('error' => true, 'result' => '此用户已经绑定到其他商家，请换一个！');
			}
			$uid = $user['id'];
		}
		else
		{
						$mail = logic('misc')->rndString().'@apiz.org';
			$rr = account()->Register($username, $password, $mail);
			if ($rr['error'])
			{
				return array('error' => true, 'result' => $rr['result']);
			}
			$uid = $rr['result'];
						account()->Validated($uid);
						user($uid)->set('role_id', 6);
		}
		return array('error' => false, 'result' => $uid);
	}
	
	public function U2SID($uid = null)
	{
		is_null($uid) && $uid = user()->get('id');
		if ($uid < 0) return $uid;
		$seller = $this->GetOne(null, $uid, null);
		return $seller ? $seller['id'] : -1;
	}
	
	public function AVParser(&$product)
	{
		if ( ! $product ) return false;
		if ( is_array($product[0]) )
		{
			$returns = array();
			foreach ( $product as $i => &$one )
			{
				$this->AVParser($one);
			}
			return;
		}
		$pid = $product['id'];
		if ($product['type'] == 'ticket')
		{
						$product['views']['tikCount'] = array(
				'TICK_STA_Used' => logic('coupon')->Count(null, $pid, null, TICK_STA_Used),
				'TICK_STA_Unused' => logic('coupon')->Count(null, $pid, null, TICK_STA_Unused),
				'TICK_STA_Overdue' => logic('coupon')->Count(null, $pid, null, TICK_STA_Overdue),
				'TICK_STA_Invalid' => logic('coupon')->Count(null, $pid, null, TICK_STA_Invalid)
			);
		}
		elseif ($product['type'] == 'stuff')
		{
			$product['views']['delivery'] = array(
				'sended' => logic('delivery')->Count($pid, DELIV_SEND_Yes),
				'waiting' => logic('delivery')->Count($pid, DELIV_SEND_No),
				'finished' => logic('delivery')->Count($pid, DELIV_SEND_OK)
			);
		}
		$fbase = 'productid='.$pid.' AND pay='.ORD_PAID_Yes.' AND status='.ORD_STA_Normal;
		$product['views']['money'] = array(
			'all' => logic('order')->Summary($fbase),
			'real' => 0
		);
		if ($product['type'] == 'ticket')
		{
			$product['views']['money']['real'] = logic('coupon')->Summary($pid);
		}
		elseif ($product['type'] == 'stuff')
		{
			$product['views']['money']['real'] = logic('order')->Summary($fbase.' AND process IN("WAIT_BUYER_CONFIRM_GOODS","TRADE_FINISH")');
		}
	}
	
		public function money_add($sid, $money)
	{
		dbc(DBCMax)
			->update('seller')
			->data('money = money + '.$money)
			->where('id='.$sid)
		->done();
	}
	public function money_less($sid, $money)
	{
		dbc(DBCMax)
			->update('seller')
			->data('money = money - '.$money)
			->where('id='.$sid)
		->done();
	}
	public function order_success($sid)
	{
		dbc(DBCMax)
			->update('seller')
			->data('successnum = successnum + 1')
			->where('id='.$sid)
		->done();
	}
	public function order_failed($sid)
	{
		dbc(DBCMax)
			->update('seller')
			->data('successnum = successnum - 1')
			->where('id='.$sid)
		->done();
	}
	public function product_add($sid)
	{
		dbc(DBCMax)
			->update('seller')
			->data('productnum = productnum + 1')
			->where('id='.$sid)
		->done();
	}
	public function product_del($sid)
	{
		dbc(DBCMax)
			->update('seller')
			->data('productnum = productnum - 1')
			->where('id='.$sid)
		->done();
	}

	
	public function maplocation4google2sogou($w, $sellerid)
	{
		$seller = $this->GetOne($sellerid);
		$map = $seller['sellermap'];
		list($x,$y, $z) = explode(',', $map);
		if ($x && $y && $z)
		{
			$r = dfopen('http:/'.'/api.go2map.com/engine/api/translate/xml?points='.$y.','.$x.'&type=2');
			if (stristr($r, '<status>error</status>') || !stristr($r, '<status>ok</status>'))
			{
				return 'false';
			}
			preg_match_all('/<x>(.*?)<\/x>/i', $r, $m);
			$x = $m[1][0];
			preg_match_all('/<y>(.*?)<\/y>/i', $r, $m);
			$y = $m[1][0];
			$map_new = $x.','.$y.','.$z;
			dbc(DBCMax)->update('seller')->data('sellermap="'.$map_new.'"')->where('id='.(int)$sellerid)->done();
			return 'ok';
		}
		else
		{
			return 'false';
		}
	}
}
?>