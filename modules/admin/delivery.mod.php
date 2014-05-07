<?php

/**
 * 模块：配送管理
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name delivery.mod.php
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
        header('Location: ?mod=delivery&code=vlist');
    }
    function vList()
    {
        $alsend = get('alsend', 'txt');
        $alsend = ($alsend == 'yes') ? DELIV_SEND_Yes : (($alsend == 'no') ? DELIV_SEND_No : DELIV_SEND_OK);
        $list = logic('delivery')->GetList($alsend);
        include handler('template')->file('@admin/delivery_list');
    }
    function Process()
    {
        $oid = get('oid', 'number');
        $order = logic('order')->GetOne($oid);
        if (!$order)
        {
            $this->Messager(__('找不到相关订单！'));
        }
        $user = user($order['userid'])->get();
        $payment = logic('pay')->SrcOne($order['paytype']);
        $express = logic('express')->SrcOne($order['expresstype']);
        $address = logic('address')->GetOne($order['addressid']);
        include handler('template')->file('@admin/delivery_process');
    }
    function Upload_single()
    {
        logic('delivery')->Invoice(get('oid', 'number'), get('no', 'txt')) && exit('ok');
    }
}

?>