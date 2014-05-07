<?php

/**
 * 逻辑区：产品相关
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package logic
 * @name product.logic.php
 * @version 1.1
 */
 
class ProductLogic
{
	
	public function display($category = '1')
	{
		$productID = get('view', 'int');
		if ( false == $productID )
		{
			$mutiView = true;
			if (ini('ui.igos.pager'))
			{
				
			}
			else
			{
				$_GET[EXPORT_GENEALL_FLAG] = EXPORT_GENEALL_VALUE;
			}
			$product = logic('product')->GetList(logic('misc')->City('id'), PRO_ACV_Yes, $category);
		}
		else
		{
			$mutiView = false;
			$product = logic('product')->GetOne($productID);
		}
				$usePager = get('page', 'int') > 0 ? true : false;
		if ( !$usePager && false == $product )
		{
			return false;
		}
				if ( !$usePager && $mutiView && count($product) == 1 )
		{
			$mutiView = false;
			$product = $product[0];
						$_GET['view'] = $product['id'];
		}
		else if (ini('ui.igos.dsper') && $mutiView && count($product) > 1)
		{
			logic('product')->reSort($product);
		}
		$file = $mutiView ? 'home' : 'detail';
		return array(
			'mutiView' => $mutiView,
			'file' => $file,
			'product' => $product
		);
	}
	
	function GetOne( $id, $cached = true )
	{
		$ckey = 'product.getone.'.$id;
		$list = $cached ? cached($ckey) : false;
		if ($list) return $list;
		$sql = 'SELECT p.*,s.sellername,s.sellerphone,s.selleraddress,s.sellerurl,s.sellermap
		FROM
			' . table('product') . ' p
		LEFT JOIN ' . table('seller') . ' s
		ON
			(p.sellerid = s.id)
		WHERE
			p.id = ' . (int)$id;
		return cached($ckey, $this->__parse_result(dbc(DBCMax)->query($sql)->limit(1)->done()));
	}
	
	public function GetFirst()
	{
		$list = $this->GetList(logic('misc')->City('id'), PRO_ACV_Yes);
		return $list[0];
	}
	
	public function get_list_ext_sql($sql = null)
	{
		static $ssql = '';
		if (is_null($sql))
		{
			$sql = $ssql;
			$ssql = '';
			return $sql;
		}
		else
		{
			return $ssql = $sql;
		}
	}
	
	function GetList( $cid = -1, $actived = null, $extend = '1' )
	{
		$sql_limit_city = '1';
		if ( $cid > 0 )
		{
			$sql_limit_city = '(p.display = '.PRO_DSP_Global.' OR (p.display = '.PRO_DSP_City.' AND p.city = ' . $cid . ') )';
		}
		$sql_limit_actived = '1';
		$now = time();
		if ( !is_null($actived) )
		{
			if ($actived === PRO_ACV_Yes)
			{
				$sql_limit_actived = 'p.begintime < ' . $now . ' AND p.overtime > ' . $now;
			}
			else
			{
				$sql_limit_actived = 'p.overtime < ' . $now;
			}
		}
		$sql = 'SELECT p.*,s.sellername,s.sellerphone,s.selleraddress,s.sellerurl,s.sellermap,p.totalnum'.$this->get_list_ext_sql().'
		FROM
			' . table('product') . ' p
		LEFT JOIN ' . table('seller') . ' s
		ON
			(p.sellerid=s.id)
		WHERE
			' . $sql_limit_actived . '
		AND
			' . $sql_limit_city . '
		AND
			' . $extend . '
		AND
			' . logic('city')->place_sql_filter() . '
		AND
			' . logic('isearcher')->product_sql_filter() . '
		AND
			p.saveHandler = "normal"
		ORDER BY
			' . logic('sort')->product_sql_filter();
		logic('isearcher')->Linker($sql);
		$sql = page_moyo($sql);
		$result = dbc(DBCMax)->query($sql)->done();
		return $this->__parse_result($result);
	}
	
	public function SrcOne($id)
	{
		return dbc(DBCMax)->select('product')->where('id='.$id)->limit(1)->done();
	}
	
	public function Where($sql_limit)
	{
		$sql = 'SELECT * FROM '.table('product').' WHERE '.$sql_limit;
		return dbc(DBCMax)->query($sql)->done();
	}
	
	public function Update($id, $array)
	{
		zlog('product')->update($id, $array);
		dbc()->SetTable(table('product'));
		if(isset($array['@extra'])) unset($array['@extra']);
		dbc()->Update($array, 'id = '.$id);
	}
	
	public function Update_direct($id, $array)
	{
		dbc()->SetTable(table('product'));
		if(isset($array['@extra'])) unset($array['@extra']);
		dbc()->Update($array, 'id = '.$id);
	}
	
	public function Delete($id)
	{
		$p = $this->SrcOne($id);
		zlog('product')->delete($id, $p);
				$imgs = explode(',', $p['img']);
		foreach ($imgs as $i => $iid)
		{
			logic('upload')->Delete($iid);
		}
				dbc(DBCMax)->delete('product')->where('id='.$id)->done();
				$sqls = array(
						'DELETE FROM '.table('finder').' WHERE productid='.$id,
						'DELETE FROM '.table('ticket').' WHERE productid='.$id,
		);
				$orderList = logic('order')->Where('productid='.$id);
		foreach ($orderList as $i => $order)
		{
			$oid = $order['orderid'];
						$sqls[] = 'DELETE FROM '.table('order').' WHERE orderid='.$oid;
						$sqls[] = 'DELETE FROM '.table('order_clog').' WHERE sign='.$oid;
						$sqls[] = 'DELETE FROM '.table('paylog').' WHERE sign='.$oid;
		}
		foreach ($sqls as $i => $sql)
		{
			dbc(DBCMax)->query($sql)->done();
		}
		logic('seller')->product_del($p['sellerid']);
		return true;
	}
	
	public function Publish($data)
	{
		logic('seller')->product_add($data['sellerid']);
		dbc()->SetTable(table('product'));
		$id = dbc()->Insert($data);
		zlog('product')->publish($id, $data);
		return $id;
	}
	
	function MoneySaves()
	{
		$now = time();
		$sql = 'SELECT SUM((price-nowprice)*(virtualnum+totalnum)) AS saves
		FROM
			' . table('product') . '
		WHERE
			overtime < ' . $now . '
		AND
			status = 2';
		$result = dbc(DBCMax)->query($sql)->limit(1)->done();
		return $result['saves'];
	}
	
	function SellsCount( $id )
	{
		$sql = 'SELECT SUM(productnum) AS sums
		FROM
			' . table('order') . '
		WHERE
			productid=' . intval($id) . '
		AND
			'.logic('pay')->OrderPaidSQL().'
		AND
			status = '.ORD_STA_Normal;
		$result = dbc(DBCMax)->query($sql)->limit(1)->done();
		return (int)$result['sums'];
	}
	
	function BuyersCount( $id )
	{
		$sql = 'SELECT COUNT(1) AS cnts
		FROM
			' . table('order') . '
		WHERE
			productid = ' . intval($id) . '
		AND
			'.logic('pay')->OrderPaidSQL().'
		AND
			status = '.ORD_STA_Normal;
		$result = dbc(DBCMax)->query($sql)->limit(1)->done();
		return (int)$result['cnts'];
	}
	
	function Surplus( $maxnum, $sells )
	{
		$surplusnum = $maxnum - $sells;
		return $surplusnum;
	}
	
	function BuysCheck( $id, $checkIfBuyed = true, $curBuys = false, $ord_is_Paid = null )
	{
		if (!$id) return array('false' => __('请选择你要购买的产品！'));
		$sql = 'SELECT *
		FROM
			' . table('product') . '
		WHERE
			id = ' . $id;
		$product = dbc(DBCMax)->query($sql)->limit(1)->done();
		if (!$product['id']) return array('false' => __('没有找到相应的产品！'));
		$error = '';
		$now = time();
		if ( $product['begintime'] > $now ) $error = __('团购还没有开始哦！');
		if ( $product['overtime'] < $now ) $error = __('团购已经结束了哦！');
		if ( $product['maxnum'] > 0 )
		{
			$surplus = $this->Surplus($product['maxnum'], $this->SellsCount($id));
			if ($curBuys && $ord_is_Paid === ORD_PAID_Yes)
			{
				$surplus += $curBuys;
			}
			$surplus || $error = __('该产品已经卖完了！下次请赶早');
			($curBuys && $curBuys > $surplus) && $error = __('该产品库存已经不足，请重新下单购买！');
		}
		if ( $checkIfBuyed && $product['multibuy'] == 'false' )
		{
			if ( $this->AlreadyBuyed($id, user()->get('id')) ) $error = __('您已经购买过此产品了哦！');
		}
		if ( $error != '' )
		{
			return array( 
				'false' => $error 
			);
		}
		return $this->__parse_result($product);
	}
	
	function AlreadyBuyed( $id, $uid )
	{
		$sql = '
		SELECT
			orderid
		FROM
			' . table('order') . '
		WHERE
			productid = ' . $id . '
		AND
			userid= ' . $uid . '
		AND
			pay=1';
		$result = dbc()->Query($sql)->GetRow();
		return $result ? true : false;
	}
	
	function Maintain($pid = false)
	{
		logic('product')->UpdateSTATUS();
		if ($pid)
		{
			$product = logic('product')->GetOne($pid, false);
			if ($product['succ_remain'] <= 0)
			{
								logic('order')->findSuccess($pid);
			}
						$sellsCount = $this->SellsCount($pid);
			if ($sellsCount)
			{
				$this->Update_direct($pid, array('sells_count' => $sellsCount));
			}
		}
	}
	
	function UpdateSTATUS()
	{
		$now = time();
		$sqls = array(
						'UPDATE '.table('product').' SET status='.PRO_STA_Failed.' WHERE successnum>(virtualnum+totalnum) AND overtime<'.$now.' AND begintime<'.$now,
						'UPDATE '.table('product').' SET status='.PRO_STA_Finish.' WHERE successnum<=(virtualnum+totalnum) AND overtime<'.$now.' AND begintime<'.$now,
						'UPDATE '.table('product').' SET status='.PRO_STA_Normal.' WHERE successnum>(virtualnum+totalnum) AND overtime>'.$now.' AND begintime<'.$now,
						'UPDATE '.table('product').' SET status='.PRO_STA_Success.' WHERE successnum<=(virtualnum+totalnum) AND overtime>'.$now.' AND begintime<'.$now,
		);
		$r = 0;
		foreach ($sqls as $i => $sql)
		{
			$r += dbc(DBCMax)->query($sql)->done();
		}
		$r && zlog('product')->maintain($r);
	}
	
	private function __parse_result( $product )
	{
		if ( ! $product ) return false;
		if ( is_array($product[0]) )
		{
			$returns = array();
			foreach ( $product as $i => $one )
			{
				$returns[] = $this->__parse_result($one);
			}
			return $returns;
		}
				$product['price'] *= 1;
		$product['nowprice'] *= 1;
		if ( $product['nowprice'] > 0 )
		{
			$product['discount'] = round(10 / ($product['price'] / $product['nowprice']), 1);
		}
		else
		{
			$product['discount'] = 0;
		}
		if ( $product['discount'] <= 0 ) $product['discount'] = 0;
		$product['time_remain'] = $product['overtime'] - time();
		$product['succ_total'] = $product['successnum'];
				if ($product['type'] == 'prize')
		{
			$product['succ_real'] = logic('prize')->sigCount('pid='.$product['id']);
		}
		else
		{
			$product['succ_real'] = $this->BuyersCount($product['id']);
		}
		$product['succ_buyers'] = $product['succ_real'] + $product['virtualnum'];
		$product['succ_remain'] = $product['succ_total'] - $product['succ_buyers'];
				if ($product['type'] == 'prize')
		{
			$product['sells_real'] = $product['succ_real'];
		}
		else
		{
			$product['sells_real'] = $this->SellsCount($product['id']);
		}
		$product['sells_count'] = $product['virtualnum'] + $product['sells_real'];
				if ($product['oncemin'] <= 0)
		{
			$product['oncemin'] = 1;
		}
				if ( $product['maxnum'] > 0 )
		{
			$product['surplus'] = $this->Surplus($product['maxnum'], $product['sells_real']);
		}
		else
		{
						$product['surplus'] = 9999;
		}
		
				$product['imgs'] = ($product['img'] != '') ? explode(',', $product['img']) : null;
		$product['img'] = $product['imgs'][0];
				$product['sellermap'] = explode(',', $product['sellermap']);
				if ($product['type'] == 'stuff')
		{
			$product['weightsrc'] = $product['weight'];
			$product['weightunit'] = ($product['weight'] >= 1000) ? 'kg' : 'g';
			$product['weight'] *= ($product['weightunit'] == 'kg') ? 0.001 : 1;
		}
				$this->PresellParser($product);
				return $product;
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
		$base = 'productid='.$product['id'];
		$STA_Normal = 'status='.ORD_STA_Normal;
		$product['mny_all'] = (float)logic('order')->Summary($base.' AND '.$STA_Normal);
		$product['mny_paid'] = (float)logic('order')->Summary($base.' AND pay='.ORD_PAID_Yes.' AND '.$STA_Normal);
		$product['mny_waited'] = (float)logic('order')->Summary($base.' AND pay='.ORD_PAID_No.' AND '.$STA_Normal);
		$product['mny_refund'] = (int)logic('order')->Summary($base.' AND status='.ORD_STA_Refund);
	}
	
	private function PresellParser(&$product)
	{
		if (isset($product['id']) && $product['id'])
		{
			$ptext = meta('p_presell_text_'.$product['id']);
			if ($ptext)
			{
				$pprice = meta('p_presell_price_full_'.$product['id']);
				$product['presell'] = array(
					'text' => $ptext,
					'price_full' => $pprice
				);
			}
		}
	}
	
	public function PresellSubmit($id)
	{
		if (post('presell_is'))
		{
			meta('p_presell_text_'.$id, post('presell_text'));
			meta('p_presell_price_full_'.$id, post('presell_price'));
		}
		else
		{
			meta('p_presell_text_'.$id, null);
			meta('p_presell_price_full_'.$id, null);
		}
	}
	
	public function STA_Name($STA_Code)
	{
		$STA_NAME_MAP = array(
			PRO_STA_Failed => '已结束，团购失败',
			PRO_STA_Normal => '进行中，未成团',
			PRO_STA_Success => '进行中，已成团',
			PRO_STA_Finish => '已结束，团购成功',
			PRO_STA_Refund => '已结束，已经返款'
		);
		return $STA_NAME_MAP[$STA_Code];
	}
	
	private function reSort($productList)
	{
		foreach ($productList as $i => $product)
		{
			if ($product['surplus'] < 0 && $product['order'] > 0)
			{
				logic('product')->Update($product['id'], array('order'=>0));
			}
		}
	}
	
	public function ClearDraft($pID, $dID, $exceptPID = false)
	{
				if ($pID)
		{
			$sql_filter = '1';
			$exceptPID && $sql_filter = 'id<>'.$exceptPID;
			$whereSQL = 'saveHandler="draft" AND draft='.$pID.' AND '.$sql_filter;
			$affCount = dbc(DBCMax)->delete('product')->where($whereSQL)->done();
			zlog('product')->draftClear($whereSQL, $affCount);
		}
		if (post('draft-pro-id')) $dID = post('draft-pro-id', 'int');
				if ($dID > 0 && $dID != $pID)
		{
			meta('p_hs_'.$dID, null);
			meta('p_ir_'.$dID, null);
			meta('expresslist_of_'.$dID, null);
			meta('paymentlist_of_'.$dID, null);
			if ($pID == 0)
			{
								$whereSQL = 'saveHandler="draft" AND id='.$dID;
				$affCount = dbc(DBCMax)->delete('product')->where($whereSQL)->done();
				zlog('product')->draftClear($whereSQL, $affCount);
			}
		}
				logic('catalog')->ProUpdate();
	}
	
	public function allowCSaveHandler($pid, $newHandler)
	{
		if (!in_array($newHandler, array('normal','draft'))) return false;
		$product = logic('product')->SrcOne($pid);
		if (!in_array($product['saveHandler'], array('normal','draft'))) return false;
		if ($product['saveHandler'] == 'normal' && $newHandler == 'draft') return false;
		return true;
	}
	
	public function GetDraftCount()
	{
		$r = dbc(DBCMax)->select('product')->in('COUNT(1) AS DrfCount')->where('saveHandler="draft"')->limit(1)->done();
		return $r['DrfCount'];
	}
	
	public function GetDraftList()
	{
		return dbc(DBCMax)->select('product')->where('saveHandler="draft"')->done();
	}
	
	public function CheckProductDraft($pid)
	{
		return dbc(DBCMax)->select('product')->where('saveHandler="draft" AND draft='.$pid)->order('addtime.DESC')->limit(1)->done();
	}

	
	function productCheck($id,$city=''){		$id = (is_numeric($id) ? $id : 0);
		$now = time();
		if($city!=''){
			$sql='select * from '.TABLE_PREFIX.'tttuangou_product where begintime <= '.$now.' and overtime > '.$now.' and id = '.$id.' and (city = '.floatval($city).' or display = 2)';
		}else{
			$sql='select * from '.TABLE_PREFIX.'tttuangou_product where begintime <= '.$now.' and overtime > '.$now.' and id = '.$id;
		}
		$query = dbc()->Query($sql);
		if (!$query)
		{
			return false;
		}
		$product=$query->GetRow();
				$product['price'] *= 1;
		$product['nowprice'] *= 1;
		return $product;
	}
	function AddSellerProNum($sellerid){
		$sql='update '.TABLE_PREFIX.'tttuangou_seller set productnum = productnum + 1 where id = '.floatval($sellerid);
		$query = dbc()->Query($sql); 
		return true;
	}
	function DelSellerProNum($sellerid){
		$sql='update '.TABLE_PREFIX.'tttuangou_seller set productnum = productnum - 1 where id = '.floatval($sellerid);
		$query = dbc()->Query($sql); 
		return true;
	}
	function AddSellerSucNum($sellerid){
		$sql='update '.TABLE_PREFIX.'tttuangou_seller set successnum = successnum + 1 where id = '.floatval($sellerid);
		$query = dbc()->Query($sql); 
		return true;
	}
	function AddSellerTotMoney($sellerid,$money){
		$sql='update '.TABLE_PREFIX.'tttuangou_seller set money = money + '.$money.' where id = '.floatval($sellerid);
		$query = dbc()->Query($sql); 
	}
function delSellerTotMoney($sellerid,$money){
		$sql='update '.TABLE_PREFIX.'tttuangou_seller set money = money - '.$money.' where id = '.floatval($sellerid);
		$query = dbc()->Query($sql); 
	}
	
}
?>