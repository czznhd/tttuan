<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename voa.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:50 $
 *******************************************************************/ 
 



class ModuleObject extends MasterObject
{
	function ModuleObject($config)
	{
		$this->MasterObject($config);		Load::logic('product');
		$this->ProductLogic = new ProductLogic();
		Load::logic('pay');
		$this->PayLogic = new PayLogic();
		Load::logic('me');
		$this->MeLogic = new MeLogic();
		Load::logic('order');
		$this->OrderLogic = new OrderLogic();
		$this->config =$config;
		Load::moduleCode($this);$this->Execute();
	}
	function Execute()
	{
		switch($this->Code)
		{
			case 'orderverify':
				$this->OrderVerify();
				break;
			default:
				$this->Main();
				break;
		}
	}
	function Main()
	{
		$this->OrderVerify();
	}
	function OrderVerify()
	{
		$op = $_GET['op'];
		if ('create' == $op)
		{
			extract($this->Get);
			$mov = (int)$mov;
			for ($i=1; $i<=$mov; $i++)
			{
				logic('coupon')->Create($pid, $oid, $uid);
			}
			$i --;
			echo '已补单'.$i.'个';
			return;
		}
		$sql = 'SELECT o.*,m.username,m.phone,p.name FROM '.TABLE_PREFIX.'tttuangou_order o LEFT JOIN '.TABLE_PREFIX.'system_members m ON(m.uid=o.userid) LEFT JOIN '.TABLE_PREFIX.'tttuangou_product p ON(p.id=o.productid) WHERE p.type="ticket" AND o.pay=1 AND o.status='.ORD_STA_Normal.' AND o.process="TRADE_FINISHED"';
		$query = $this->DatabaseHandler->Query($sql);
		if ($query)
		{
			$orders = $query->GetAll();
			$finds = array();
			foreach ($orders as $i => $order)
			{
				$tickSql = 'SELECT COUNT(*) AS tickCount FROM '.TABLE_PREFIX.'tttuangou_ticket WHERE orderid='.$order['orderid'].' AND uid='.$order['userid'];
				$result = $this->DatabaseHandler->Query($tickSql)->GetRow();
				$tickCount = $result['tickCount'];
				if ($order['productnum'] != $tickCount)
				{
					$order['tickCount'] = $tickCount;
					$order['tickMov'] = $order['productnum']-$tickCount;
					$finds[] = $order;
				}
			}
		}
		include(handler('template')->file('@admin/voa_order_verify'));
	}
}
?>