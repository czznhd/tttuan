<?php

/**
 * 模块：充值管理
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name recharge.mod.php
 * @version 1.0
 */

class ModuleObject extends MasterObject
{
    public function ModuleObject( $config )
    {
        $this->MasterObject($config);
        $runCode = Load::moduleCode($this);
        $this->$runCode();
    }
    function Card()
    {
        $used = get('used', 'number');
        is_numeric($used) || $used = -1;
        $list = logic('recharge')->card()->GetList($used);
        include handler('template')->file('@admin/recharge_card');
    }
    function Card_generate()
    {
        include handler('template')->file('@admin/recharge_card_generate');
    }
    function Card_generate_ajax()
    {
        $price = get('price', 'number');
        $nums = get('nums', 'int');
        logic('recharge')->card()->Generate($price, $nums);
        exit('ok');
    }
    function Card_delete()
    {
        $id = get('id', 'int');
        $affect = logic('recharge')->card()->Delete($id);
        exit($affect > 0 ? 'ok' : 'fail');
    }
    function Order_clean()
    {
        $ckey = 'business.recharge.order.clean.lock';
        fcache($ckey, dfTimer('com.recharge.order.clean')) && exit('no');
        $cleans = logic('recharge')->Clean();
        fcache($ckey, 'DNA.'.md5(time()));
        $rel = $cleans > 0 ? '（系统已经自动清理掉 '.$cleans.' 个过期的充值流水号）' : 'no';
        exit($rel);
    }
    
    public function order()
    {
        $paystatus = get('paystatus');
        if (is_numeric($paystatus))
        {
            if ((int)$paystatus < 1)
            {
                $where = 'paytime = 0';
            }
            else
            {
                $where = 'paytime > 0';
            }
        }
        else
        {
            $where = '1';
        }
        $list = logic('recharge')->GetList($where);
        include handler('template')->file('@admin/recharge_order');
    }
    
    public function order_confirm()
    {
        $orderid = get('orderid', 'number');
        if ($orderid)
        {
            $r = logic('recharge')->MakeSuccessed($orderid);
            exit('ok');
        }
        else
        {
            exit('充值订单号不正确');
        }
    }
}

?>