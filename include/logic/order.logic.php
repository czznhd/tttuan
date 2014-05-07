<?php

/**
 * 逻辑区：订单管理
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package logic
 * @name order.logic.php
 * @version 1.1
 */

class OrderLogic
{
	
	public function GetFree($uid = 0, $product_id = 0)
	{
		if ($uid == 0)
		{
			$uid = user()->get('id');
		}
		if ($product_id == 0)
		{
			return false;
		}
				$order = $this->Where('userid='.$uid.' AND status=255');
		if ($order)
		{
			$order = $order[0];
		}
		else
		{
									
				$order = $this->__CreateNew($uid, $product_id);
			
		}
		return $order;
	}
	
	private function __CreateNew($uid, $product_id)
	{
		$array = array(
			'orderid' => $this->__GetFreeID(),
			'productid' => $product_id, 
			'userid' => $uid,
			'buytime' => time(),
			'status' => 255
		);
		dbc()->SetTable(table('order'));
		dbc()->Insert($array);
		return $array;
	}
	
	private function __GetFreeID()
	{
		$id = date('Ymd', time()) . str_pad(rand('1', '99999'), 5, '0', STR_PAD_LEFT);
		$sql = '
		SELECT
			*
		FROM
			' . table('order') . '
		WHERE
			orderid = ' . $id;
		$order = dbc()->Query($sql)->GetRow();
		if ( empty($order) )
		{
			return $id;
		}
		else
		{
			return $this->__GetFreeID();
		}
	}
	
	public function GetOne( $id, $uid = 0 )
	{
		if ($uid > 0){
			$sql_where['userid'] = $uid;
		}

		$sql_where['orderid'] = $id;
		$data = dbc(DBCMax)->select('order')->where($sql_where)->limit(1)->done();
		
		return $this->__parse_result($data);
	}
	
	public function GetList( $uid = 0, $status = ORD_STA_ANY, $pay = ORD_PAID_ANY, $extend = '1' )
	{
		$sql_limit_user = '1';
		if ($uid > 0)
		{
			$sql_limit_user = 'userid = '.$uid;
		}
		$sql_limit_status = '1';
		if ( $status >= 0 )
		{
			$sql_limit_status =  'status = '.$status;
		}
		$sql_limit_pay = '1';
		if ( $pay >= 0 )
		{
			$sql_limit_pay = 'pay = '.$pay;
		}
		$sql = 'SELECT o.*, m.username
		FROM
			' . table('order') .' o
		LEFT JOIN
			' .table('members'). ' m
		ON
			(o.userid=m.uid)
		WHERE
			'.$sql_limit_user.'
		AND
			'.$sql_limit_status.'
		AND
			'.$sql_limit_pay.'
		AND
			'.$extend.'
		ORDER BY
			buytime
		DESC';
		logic('isearcher')->Linker($sql);
		$sql = page_moyo($sql);
		$order = dbc(DBCMax)->query($sql)->done();
		return $this->__parse_result($order);
	}
	
	public function SrcOne($id)
	{
		$sql = 'SELECT * FROM '.table('order').' WHERE orderid='.$id;
		return dbc()->Query($sql)->GetRow();
	}
	
	public function SrcList( $uid = USR_ANY, $status = ORD_STA_ANY, $pay = ORD_PAID_ANY, $extend = '1' )
	{
		$sql_limit_user = '1';
		if ($uid > 0)
		{
			$sql_limit_user = 'userid = '.$uid;
		}
		$sql_limit_status = '1';
		if ( $status >= 0 )
		{
			$sql_limit_status =  'status = '.$status;
		}
		$sql_limit_pay = '1';
		if ( $pay >= 0 )
		{
			$sql_limit_pay = 'pay = '.$pay;
		}
		$sql = '
		SELECT
			*
		FROM
			' . table('order') .'
		WHERE
			'.$sql_limit_user.'
		AND
			'.$sql_limit_status.'
		AND
			'.$sql_limit_pay.'
		AND
			'.$extend.'
		ORDER BY
			buytime
		DESC';
		$order = dbc()->Query($sql)->GetAll();
		return $order;
	}
	
	public function Where($sql_limit)
	{
		$sql = '
		SELECT
			*
		FROM
			'.table('order').'
		WHERE
			'.$sql_limit.'
		';
		return dbc()->Query($sql)->GetAll();
	}
	
	public function Update($id, $array)
	{
		dbc()->SetTable(table('order'));
		dbc()->Update($array, 'orderid = '.$id);
	}
	
	public function Delete($id)
	{
		$sqls = array();
				$sqls[] = 'DELETE FROM '.table('order').' WHERE orderid='.$id;
				$sqls[] = 'DELETE FROM '.table('order_clog').' WHERE sign='.$id;
				$sqls[] = 'DELETE FROM '.table('paylog').' WHERE sign='.$id;
				$sqls[] = 'DELETE FROM '.table('ticket').' WHERE orderid='.$id;
		foreach ($sqls as $i => $sql)
		{
			dbc()->Query($sql);
		}
		return true;
	}
	
	public function Count($where)
	{
		$sql = 'SELECT COUNT(1) AS CNT FROM '.table('order').' WHERE '.$where;
		$result = dbc()->Query($sql)->GetRow();
		return $result['CNT'];
	}
	
	public function Summary($where)
	{
						$sql = 'SELECT SUM(paymoney) as TTL FROM '.table('order').' WHERE '.$where;
		$result = dbc(DBCMax)->query($sql)->limit(1)->done();
		return $result['TTL'] ? $result['TTL'] : 0;
	}
	
	public function _TMP_Payed($trade, $payment = false)
	{
		$id = $trade['sign'];
		$order = $this->GetOne($id);

		if ($trade['status'] == 'WAIT_SELLER_SEND_GOODS' && $payment['config']['service'] == 'medi') {
			$trade['price'] = $order['paymoney'];
			$trade['money']= $trade['price'];
		}

		if ($order['pay'] == ORD_PAID_No)
		{
			$product = logic('product')->BuysCheck($order['productid'], false, $order['productnum'], ORD_PAID_No);
			$error = false;
			isset($product['false']) && $error = $product['false'];
			
						if ($trade['money'] > 0)
			{
				$rcgPrice = $trade['price'];
				$rcgDSP = true;
			}
			else
			{
				if (!$trade['nmadd'])
				{
					$rcgPrice = $trade['price'];
					$rcgDSP = false;
				}
			}
			$order['_tmpd_'] = logic('recharge')->forder()->paid($trade, $order, $rcgPrice, $rcgDSP);
						if (!$trade['nmpay'])
			{
				$money = round(logic('me')->money()->count($order['userid']), 2);
				$price = round($order['totalprice'], 2);
				if ($error)
				{
					
				}
				else
				{
					if ($order['_tmpd_']['paid'] === false)
					{
						$error = '订单支付失败，也许您之前已经支付过此订单了！';
					}
					elseif ($order['status'] != ORD_STA_Normal)
					{
						$error = '订单状态非正常，已经无法支付！';
					}
					elseif ($money >= $price)
					{
						$express = '';
						if ($order['expressprice'] > 0)
						{
							$express = sprintf(__('运费：%.2f'), $order['expressprice']);
						}
						logic('me')->money()->pay($price, $order['userid'], array(
							'name' => __('团购商品'),
							'intro' => sprintf(__('商品名：%s<br/>单价：%.2f<br/>数目：%d<br/>%s'), $order['product']['flag'], $order['productprice'], $order['productnum'], $express)
						));
												$order_update = array(
							'pay' => ORD_PAID_Yes,
							'paytime' => time()
						);
						$this->Update($id, $order_update);
												logic('order')->doSuccess($order);
												if (in_array($order['process'], array('__CREATE__', 'WAIT_BUYER_PAY')))
						{
							$this->Processed($order['orderid'], '__PAY_YET__');
							logic('order')->clog($trade['sign'])->sys('订单支付系统：用户已付款');
						}
					}
					else
					{
						$error = '账户余额不足以支付此订单，请充值后重新支付或者选择重新下单！（本次支付金额已充值到您的个人账户）';
					}
				}
			}
		}
		$error && $order['false'] = $error;
		return $order;
	}
	
	public function Processed($orderid, $process)
	{
		$this->Update($orderid, array('process' => $process));
	}
	
	public function MakeSuccessed($orderid, $ignore_rules = false)
	{
		$order = $this->GetOne($orderid);
		$product = logic('product')->BuysCheck($order['productid'], false, $order['productnum'], ORD_PAID_Yes);
		if (isset($product['false']))
		{
			$order['false'] = $product['false'];
			return $order;
		}
		if (false == $ignore_rules)
		{
						logic('product')->Maintain($order['productid']);
			return $order;
		}
		if ($order['product']['type'] == 'ticket')
		{
			$tickets = logic('coupon')->GetList($order['userid'], $order['orderid'], TICK_STA_ANY);
			if (count($tickets) > 0)
			{
											}
			else
			{
								if ($order['product']['allinone'] == 'true')
				{
										logic('coupon')->Create($order['productid'], $order['orderid'], $order['userid'], $order['productnum'], false, false, 0);
				}
				else
				{
										for ($i=0; $i<$order['productnum']; $i++)
					{
						logic('coupon')->Create($order['productid'], $order['orderid'], $order['userid'], 1, false, false, $i);
					}
				}
			}
						logic('order')->Processed($order['orderid'], 'TRADE_FINISHED');
			logic('order')->clog($orderid)->sys('团购券已经生成：交易完成');
		}
		else
		{
						logic('order')->Processed($order['orderid'], 'WAIT_SELLER_SEND_GOODS');
			logic('order')->clog($orderid)->sys('用户已经下单：等待发货');
		}
				$this->doRelSuccess($order);
				$MID = 'notify_YET_'.$orderid;
		if (meta($MID))
		{
						meta($MID, null);
			return $order;
		}
		logic('notify')->Call($order['userid'], 'logic.order.MakeSuccessed', array(
			'orderid' => $order['orderid'],
			'productflag' => $order['product']['flag'],
			'productnum' => $order['productnum'],
			'productprice' => $order['productprice'],
			'buytime' => $order['buytime'],
			'paymoney' => $order['paymoney'],
			'paytime' => $order['paytime'],
			'expressprice' => $order['expressprice'],
			'extmsg' => $order['extmsg']
		));
		meta($MID, time(), 'd:30');
		return $order;
	}
	
	public function findSuccess($pid)
	{
		$process_search = 'productid = '.$pid.' AND process = "__PAY_YET__"';
		$order_list = logic('order')->SrcList(USR_ANY, ORD_STA_Normal, ORD_PAID_Yes, $process_search);
		if (count($order_list) > 0)
		{
			foreach ($order_list as $i => $order_one)
			{
				logic('order')->MakeSuccessed($order_one['orderid'], true);
			}
		}
	}
	
	private function __parse_result($data)
	{
		if ( ! $data ) return false;
		if ( is_array($data[0]) )
		{
			$return = array();
			foreach ( $data as $i => $one )
			{
				$return[] = $this->__parse_result($one);
			}
			return $return;
		}
		$data['product'] = logic('product')->GetOne($data['productid']);
		$data['attrs'] = logic('attrs')->snapshot($data['orderid'], $data['productid']);
		return $data;
	}
	
	public function STA_Name($STA_Code)
	{
		$STA_NAME_MAP = array(
			ORD_STA_ANY => '任意状态',
			ORD_STA_Cancel => '已经取消',
			ORD_STA_Failed => '订单失败',
			ORD_STA_Normal => '订单正常',
			ORD_STA_Overdue => '已经过期',
			ORD_STA_Refund => '已经返款'
		);
		return $STA_NAME_MAP[$STA_Code];
	}
	
	public function PROC_Name($PRO_Code)
	{
		$PROC_NAME_MAP = array(
			'*' => '任意进程',
			'__CREATE__' => '创建订单',
			'__PAY_YET__' => '已经付款',
			'WAIT_BUYER_PAY' => '等待付款',
			'WAIT_SELLER_SEND_GOODS' => '等待发货',
			'WAIT_BUYER_CONFIRM_GOODS' => '等待收货',
			'TRADE_FINISHED' => '交易完成'
		);
		$POCN = $PROC_NAME_MAP[$PRO_Code];
		return $POCN ? $POCN : $PRO_Code;
	}
	
	public function clog($oid)
	{
		$obj = loadInstance('logic.order.clog', 'OrderLogic_cLog');
		$obj->CSIGN = $oid;
		return $obj;
	}
	
	public function Refund($oid, $inRFM = null)
	{
		$order = logic('order')->GetOne($oid);
				logic('order')->Update($oid, array('status'=>ORD_STA_Refund));
				logic('order')->doFailed($order);
				if ($order['product']['type'] == 'ticket')
		{
			dbc(DBCMax)->update('ticket')->where('orderid='.$order['orderid'])->data('status='.TICK_STA_Invalid)->done();
		}
				$rfm = is_null($inRFM) ? $order['paymoney'] : ($inRFM > 0 ? (float)$inRFM : 0);
		$rfm && logic('me')->money()->add($rfm, $order['userid'], array(
			'name' => __('订单退款'),
			'intro' => '您好，您的订单[ '.$oid.' ]已经退款！'
		));
				logic('notify')->Call($order['userid'], 'logic.order.Refund', array(
			'orderid' => $oid,
			'productflag' => $order['product']['flag'],
			'refundmoney' => $order['productnum']*$order['productprice']
		));
		return true;
	}
	
	public function Confirm($oid)
	{
		$order = logic('order')->SrcOne($oid);
		$product = logic('product')->SrcOne($order['productid']);
				$trade = array(
			'sign' => $oid,
			'trade_no' => time(),
			'price' => $order['paymoney'],
			'money' => 0,
			'money_reason' => '管理员 '.user()->get('name').' 确认订单已付款',
			'status' => ($product['type'] == 'ticket') ? 'TRADE_FINISHED' : 'WAIT_SELLER_SEND_GOODS'
		);
		$error = false;
		$order = logic('order')->_TMP_Payed($trade);
		isset($order['false']) && $error = ' [P] '.$order['false'];
		if (!$error)
		{
						$order = logic('order')->MakeSuccessed($trade['sign']);
			isset($order['false']) && $error = ' [M] '.$order['false'];
			if (!$error)
			{
				logic('notify')->Call($order['userid'], 'logic.order.Confirm', array(
					'orderid' => $oid
				));
								if (($order['productprice'] != 0 || $order['expressprice'] != 0) && $order['totalprice'] == 0)
				{
					$totalprice = $order['productprice']*$order['productnum']+$order['expressprice'];
					logic('order')->Update($oid, array('totalprice'=>$totalprice));
				}
			}
		}
		if ($error)
		{
			logic('order')->Update($oid, array('status'=>ORD_STA_Overdue,'process'=>'__PAY_YET__'));
			return ' [R] '.$error;
		}
		return '';
	}
	
	public function Cancel($oid, $inRFM = null)
	{
		$order = logic('order')->GetOne($oid);
		logic('order')->Update($oid, array('status'=>ORD_STA_Cancel));
		if ($order['pay'] == ORD_PAID_Yes)
		{
			$rfm = is_null($inRFM) ? $order['paymoney'] : ($inRFM > 0 ? (float)$inRFM : 0);
			$rfm && logic('me')->money()->add($rfm, $order['userid'], array(
				'name' => __('订单取消'),
				'intro' => '您好，您的订单[ '.$oid.' ]已经被取消，余款已经充值到您的个人账户！'
			));
			logic('order')->doFailed($order);
		}
		logic('notify')->Call($order['userid'], 'logic.order.Cancel', array(
			'orderid' => $oid
		));
		return true;
	}
	
	
	public function doSuccess($order)
	{
				logic('seller')->money_add($order['product']['sellerid'], $order['productnum']*$order['productprice']);
		logic('seller')->order_success($order['product']['sellerid']);
				dbc(DBCMax)->update('product')->data('totalnum=totalnum+1')->where('id='.$order['productid'])->done();
	}
	
	public function doFailed($order)
	{
				$sid = $order['product']['sellerid'];
		logic('seller')->money_less($sid, $order['productnum']*$order['productprice']);
		logic('seller')->order_failed($sid);
				dbc(DBCMax)->update('product')->data('totalnum=totalnum-1')->where('id='.$order['productid'])->done();
	}
	
	public function doRelSuccess($order)
	{
		logic('me')->finder($order['userid'], $order['productid']);
	}

	
	public function recent_buys()
	{
		$cache = fcache('logic.order.recentbuys', 3600);
		if (!$cache)
		{
			$buys = array();
			$get_order_sql = 'select productid,userid from '.table('order').' where `process` not in ("__CREATE__","WAIT_BUYER_PAY") order by buytime desc limit 30';
			$orders = dbc(DBCMax)->query($get_order_sql)->done();
			$proids = array();
			$usrids = array();
			foreach ($orders as $order)
			{
				$proids[] = $order['productid'];
				$usrids[] = $order['userid'];
			}
						$pros = array();
			$get_pro_sql = 'select id,flag from '.table('product').' where id in('.implode(',', $proids).')';
			$result = dbc(DBCMax)->query($get_pro_sql)->done();
			foreach ($result as $pro)
			{
				$pros[$pro['id']] = $pro['flag'];
			}
						$usrs = array();
			$get_usr_sql = 'select uid,username,phone from '.table('members').' where uid in('.implode(',', $usrids).')';
			$result = dbc(DBCMax)->query($get_usr_sql)->done();
			foreach ($result as $usr)
			{
				$usrs[$usr['uid']] = array('name' => $usr['username'], 'phone' => $usr['phone']);
			}
						foreach ($orders as $order)
			{
				$buys[] = array(
					'product_id' => $order['productid'],
					'product_name' => $pros[$order['productid']],
					'user_id' => $order['userid'],
					'user_name' => $usrs[$order['userid']]['name'],
					'user_phone' => $usrs[$order['userid']]['phone']
				);
			}
						foreach ($buys as $i => $buy)
			{
				$buys[$i]['user_name'] = $buy['user_name'] ? $this->recent_buys_mosaic($buy['user_name']) : '匿名用户';
				$buys[$i]['user_phone'] = $this->recent_buys_mosaic($buy['user_phone'] ? $buy['user_phone'] : $this->recent_buys_g_phone());
			}
			fcache('logic.order.recentbuys', $buys);
			$cache = $buys;
		}
		return $cache;
	}

	
	private function recent_buys_mosaic($string)
	{
		$len = $this->recent_buys_strlen($string);
		$keep = intval($len / 1.5);
		$keep < 2 && $keep = 2;
		$keep_f = intval($keep / 2);
		$keep_e = $keep - $keep_f;
		$ms = $len - $keep;
		$mosaic = $this->recent_buys_csubstr($string, 0, $keep_f).str_repeat('*', $ms).$this->recent_buys_csubstr($string, $len - $keep_e, $keep_e);
		return $mosaic;
	}

	
	private function recent_buys_g_phone()
	{
		$prefixs = array('134','135','136','137','138','139','150','151','157','158','159','187','188','130','131','132','155','156','185','186','133','153','180','189');
		$prefix = $prefixs[mt_rand(0, count($prefixs) - 1)];
		$suffix = str_pad((string)mt_rand(10000000, 99999999), 8, STR_PAD_LEFT);
		return $prefix.$suffix;
	}

	
	private function recent_buys_strlen($string)
	{
		if (ENC_IS_GBK)
		{
			$string = ENC_G2U($string);
		}
		preg_match_all('/./us', $string, $match);
		return count($match[0]);
	}

	
	 private function recent_buys_csubstr($str, $start=0, $length, $charset=false, $suffix=false)
       {
       	$charset || $charset = ini('settings.charset');
               if(function_exists("mb_substr"))
               {
                       if(mb_strlen($str, $charset) <= $length) return $str;
                       $slice = mb_substr($str, $start, $length, $charset);
               }
               else
               {
                       $re['utf-8']   = "/[/x01-/x7f]|[/xc2-/xdf][/x80-/xbf]|[/xe0-/xef][/x80-/xbf]{2}|[/xf0-/xff][/x80-/xbf]{3}/";
                       $re['gb2312'] = "/[/x01-/x7f]|[/xb0-/xf7][/xa0-/xfe]/";
                       $re['gbk']          = "/[/x01-/x7f]|[/x81-/xfe][/x40-/xfe]/";
                       $re['big5']          = "/[/x01-/x7f]|[/x81-/xfe]([/x40-/x7e]|/xa1-/xfe])/";
                       preg_match_all($re[$charset], $str, $match);
                       if(count($match[0]) <= $length) return $str;
                       $slice = join("",array_slice($match[0], $start, $length));
               }
               if($suffix) return $slice."…";
               return $slice;
       }

	
	function orderCheck( $id )
	{
		$sql = 'select * from ' . TABLE_PREFIX . 'tttuangou_order where orderid=' . $id . ' and status=1 and pay=0';
		$query = $this->DatabaseHandler->Query($sql);
		$order = $query->GetRow();
		return $order;
	}

	function orderGetByUser( $productid, $uid, $checkMuti = false )
	{
		if ( false == $checkMuti )
		{
			$sql = 'select * from ' . TABLE_PREFIX . 'tttuangou_order where productid=' . intval($productid) . ' and userid=' . intval($uid) . ' and status=1';
		}
		else
		{
			$sql = 'select o.*,p.multibuy from ' . TABLE_PREFIX . 'tttuangou_order o LEFT JOIN ' . TABLE_PREFIX . 'tttuangou_product p ON(o.productid=p.id) where o.productid=' . intval($productid) . ' and o.userid=' . intval($uid) . '';
		}
		$query = $this->DatabaseHandler->Query($sql);
		if ( ! $query ) return '';
		$order = $query->GetRow();
		if ( $order['multibuy'] != '' && $order['multibuy'] == 'true' )
		{
						return '';
		}
		return $order;
	}

	function orderEdit( $orderid, $ary = array() )
	{
		$this->DatabaseHandler->SetTable(TABLE_PREFIX . 'tttuangou_order');
		$result = $this->DatabaseHandler->Update($ary, 'orderid=' . $orderid);
		return $result;
	}

	function orderCreater( $ary = array() )
	{
		$this->DatabaseHandler->SetTable(TABLE_PREFIX . 'tttuangou_order');
		$result = $this->DatabaseHandler->Insert($ary);
		return $result;
	}

	function orderType( $id, $type, $paytime = '', $pay = '' )
	{
		$ary = array( 
			'status' => $type 
		);
		if ( $paytime != '' ) $ary['paytime'] = time();
		if ( $pay != '' )
		{
			$ary['pay'] = 1;
			$sql = 'update ' . TABLE_PREFIX . 'tttuangou_product p left join ' . TABLE_PREFIX . 'tttuangou_order o on p.id=o.productid set p.totalnum = p.totalnum + 1 where o.orderid = ' . $id;
			$this->DatabaseHandler->Query($sql);
		}
		$this->DatabaseHandler->SetTable(TABLE_PREFIX . 'tttuangou_order');
		$result = $this->DatabaseHandler->Update($ary, 'orderid=' . $id);
		return $result;
	}

	function orderPaylist( $productid )
	{
		$sql = 'select o.*,m.username,m.email,p.nowprice,p.successnum from ' . TABLE_PREFIX . 'tttuangou_order o left join ' . TABLE_PREFIX . 'system_members m on o.userid= m.uid left join ' . TABLE_PREFIX . 'tttuangou_product p on o.productid=p.id where o.productid = ' . intval($productid) . ' and o.pay = 1 and o.status = 1';
		$query = $this->DatabaseHandler->Query($sql);
		$orderPayed = $query->GetAll();
		return $orderPayed;
	}

		function listProductOrderPayed( $productid )
	{
		$sql = 'select o.*,m.username,m.email,p.nowprice,p.successnum from ' . TABLE_PREFIX . 'tttuangou_order o left join ' . TABLE_PREFIX . 'system_members m on o.userid= m.uid left join ' . TABLE_PREFIX . 'tttuangou_product p on o.productid=p.id where o.productid = ' . intval($productid) . ' and o.pay = 1';
		$query = $this->DatabaseHandler->Query($sql);
		$orderPayed = $query->GetAll();
		return $orderPayed;
	}

	function UpOrder()
	{
		$sql = 'update ' . TABLE_PREFIX . 'tttuangou_order o left join ' . TABLE_PREFIX . 'tttuangou_product p on o.productid = p.id set o.status=2 where ' . time() . ' > p.overtime and  o.pay = 0 and o.status = 1 and o.userid = ' . MEMBER_ID;
		$query = $this->DatabaseHandler->Query($sql);
		return true;
	}

	function GetTicket( $id, $seller = 0 )
	{
		if ( $seller == 0 )
		{
			$sql = 'select t.*,p.name,p.intro,p.nowprice,s.sellerurl,p.perioddate,s.selleraddress from ' . TABLE_PREFIX . 'tttuangou_ticket t LEFT JOIN ' . TABLE_PREFIX . 'tttuangou_product p on t.productid = p.id left join ' . TABLE_PREFIX . 'tttuangou_seller s on p.sellerid=s.id where uid = ' . MEMBER_ID . ' and ticketid = ' . intval($id);
		}
		else
		{
			$sql = 'select t.*,p.name,p.intro,p.nowprice,s.sellerurl,p.perioddate,s.selleraddress from ' . TABLE_PREFIX . 'tttuangou_ticket t LEFT JOIN ' . TABLE_PREFIX . 'tttuangou_product p on t.productid = p.id left join ' . TABLE_PREFIX . 'tttuangou_seller s on p.sellerid=s.id where ticketid = ' . intval($id);
		}
		$query = dbc()->Query($sql);
		$ticket = $query->GetRow();
		return $ticket;
	}
}

/**
 * 扩充类：变更日志
 * @author Moyo <dev@uuland.org>
 */
class OrderLogic_cLog
{
	public $CSIGN = null;
	
	function sys($mark)
	{
		return $this->add('system', $mark, 0);
	}
	
	function add($action, $mark, $uid = null)
	{
		return dbc(DBCMax)->insert('order_clog')->data(array(
			'sign' => $this->CSIGN,
			'action' => $action,
			'uid' => is_null($uid) ? user()->get('id') : $uid,
			'remark' => $mark,
			'time' => time()
		))->done();
	}
	
	function del($id = null)
	{
		dbc(DBCMax)->delete('order_clog')->where('sign='.$this->CSIGN);
		if ($id !== null)
		{
			dbc(DBCMax)->where('id='.$id);
		}
		return dbc(DBCMax)->done();
	}
	
	function vlist($action = '1')
	{
		return
		dbc(DBCMax)
			->select('order_clog')
			->where('sign='.$this->CSIGN)
			->where($action)
			->order('id.desc')
		->done();
	}
}
?>
