<?php

/**
 * 模块：购买流程操作
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name buy.mod.php
 * @version 1.0
 */

class ModuleObject extends MasterObject
{
    function ModuleObject( $config )
    {
        $this->MasterObject($config);
        if (MEMBER_ID < 1)
        {
            $this->Messager(__('请先登录！'), '?mod=account&code=login');
        }
        $runCode = Load::moduleCode($this);
        $this->$runCode();
    }
    
    function Main()
    {
        header('Location: .');
    }
    
    function Checkout()
    {
        $this->Title = __('提交订单');
        $id = get('id', 'int');
        $product = logic('product')->BuysCheck($id);
        if (isset($product['false']))
        {
            $this->Messager($product['false']);
        }
        if ($product['type'] == 'prize')
        {
            header('Location: '.rewrite('?mod=prize&code=sign&pid='.$product['id']));
            exit;
        }
        include handler('template')->file('buy_checkout');
    }
    
    function Checkout_save()
    {
        $product_id = post('product_id', 'int');
        $product = logic('product')->BuysCheck($product_id);
        if (isset($product['false']))
        {
            return $this->__ajax_save_failed($product['false']);
        }
        $num_buys = post('num_buys', 'int');
        if (!$num_buys || ($product['oncemax'] > 0 && $num_buys > $product['oncemax']) || $num_buys < $product['oncemin'])
        {
            return $this->__ajax_save_failed(__('请填写正确的购买数量！'));
        }
        $order = logic('order')->GetFree(user()->get('id'), $product_id);
        $order['productnum'] = $num_buys;
        $order['productprice'] = $product['nowprice'];
        $order['extmsg'] = post('extmsg', 'txt');
        if ($product['type'] == 'stuff')
        {
            logic('address')->Accessed('order.save', $order);
            logic('express')->Accessed('order.save', $order);
        }
                logic('notify')->Accessed('order.save', $order);
				if (!logic('attrs')->Accessed('order.save', $order))
        {
            return $this->__ajax_save_failed(__('请选择正确的产品属性规格！'));
        }
                $price_total = $order['productprice'] * $order['productnum'] + $order['expressprice'];
                logic('attrs')->order_calc($order['orderid'], $price_total);
                if ((float)$price_total < 0)
        {
            return $this->__ajax_save_failed(__('订单总价不正确，请重新下单！'));
        }
                $order['totalprice'] = $price_total;
                $order['process'] = '__CREATE__';
        $order['status'] = ORD_STA_Normal;
        logic('order')->Update($order['orderid'], $order);
                $ops = array(
            'status' => 'ok',
            'id' => $order['orderid']
        );
        if (!X_IS_AJAX)
        {
        	header('Location: '.rewrite('?mod=buy&code=order&id='.$order['orderid']));
        	exit;
        }
        echo jsonEncode($ops);
    }
    
    private function __ajax_save_failed($msg)
    {
        $ops = array(
            'status' => 'failed',
            'msg' => $msg
        );
        if (!X_IS_AJAX)
        {
        	$this->Messager($msg, -1);
        }
        echo jsonEncode($ops);
        return false;    
    }
    
    function Order()
    {
        $this->Title = __('确认订单');
        $id = get('id', 'number');
        $order = logic('order')->GetOne($id);
        if (user()->get('id') != $order['userid'])
        {
            $this->Messager('对不起，您没有权限操作此订单！', '?mod=me&code=order');
        }
                $order['price_of_product'] = $order['productprice']*$order['productnum'];
        $order['price_of_total'] = $order['price_of_product'];
        logic('address')->Accessed('order.show', $order);
        logic('express')->Accessed('order.show', $order);
        logic('notify')->Accessed('order.show', $order);
		logic('attrs')->Accessed('order.show', $order);
        include handler('template')->file('buy_order');
    }
    
    function Order_save()
    {
        $order_id = post('order_id', 'number');
        $ibank = post('ibank','txt');
        $order = logic('order')->GetOne($order_id);
        if (user()->get('id') != $order['userid'])
        {
            return $this->__ajax_save_failed(__('您没有权限操作此订单！'));
        }
        if ($order['status'] != ORD_STA_Normal || $order['pay'] == ORD_PAID_Yes)
        {
            return $this->__ajax_save_failed(__('此订单已经不能支付！'));
        }
        $product = logic('product')->BuysCheck($order['productid']);
        if (isset($product['false']))
        {
            return $this->__ajax_save_failed($product['false']);
        }
        $payment_id = post('payment_id', 'int');
        
                $price_total = $order['productprice'] * $order['productnum'] + $order['expressprice'];
				logic('attrs')->order_calc($order['orderid'], $price_total);
		        $pay_money = $price_total;
        
                $pay = logic('pay')->GetOne($payment_id);
        if ($pay_money == 0 && $pay['code'] != 'self') {
            return $this->__ajax_save_failed(__('请选择余额支付'));
        }

        $me_money = user()->get('money');
        if ($payment_id == 1)
        {
            $me_money = 0;
        }
        $use_surplus = post('payment_use_surplus', 'txt');
        if ($use_surplus == 'true' && $me_money > 0)
        {
            $pay_money = $price_total - $me_money;
        }
        $array = array(
            'totalprice' => $price_total,
            'paytype' => $payment_id,
            'paymoney' => $pay_money
        );
        logic('order')->Update($order_id, $array);
        $ops = array(
            'status' => 'ok',
            'tourl'  => rewrite("?mod=buy&code=pay&id=".$order["orderid"]."&ibank=".$ibank),
        );
        if (logic('pay')->plugin_has_ext_html($payment_id) === true) {
            header('Location: '.rewrite('?mod=buy&code=pay&id='.$order_id.'&ibank='.$ibank));
            exit;
        }
    	if (!X_IS_AJAX)
        {
        	header('Location: '.rewrite('?mod=buy&code=pay&id='.$order_id.'&ibank='.$ibank));
        	exit;
        }
        echo jsonEncode($ops);
    }
    
    function Pay()
    {
        $this->Title = __('订单支付');
        $id = get('id', 'number');
        $order = logic('order')->GetOne($id);
        if (user()->get('id') != $order['userid'])
        {
            $this->Messager('对不起，您没有权限支付此订单！', '?mod=me&code=order');
        }
        if ($order['status'] != ORD_STA_Normal)
        {
        	$this->Messager(__('关于此订单：').logic('order')->STA_Name($order['status']), '?mod=me&code=order');
        }
        if ($order['paytype'] == 0)
        {
                        header('Location: '.rewrite('?mod=buy&code=order&id='.$id));
        }
        if ($order['pay'] == 1)
        {
            $this->Messager(__('此订单已经支付过了！'), '?mod=me&code=order');
        }
        $product = logic('product')->BuysCheck($order['productid']);
        if (isset($product['false']))
        {
            return $this->Messager($product['false']);
        }

                $payment_id = get('p');
        if ( is_numeric($payment_id)) {
            logic('order')->Update($id, array('paytype' => $payment_id));
        }
        
        $pay = logic('pay')->GetOne($order['paytype']);

                $rewrite_me = false;
        include(CONFIG_PATH.'rewrite.php');
        if($_rewrite['mode'] != '')  {
            $me_uname   = isset($_rewrite['value_replace_list']['mod']['me']) === false ? 'me' : $_rewrite['value_replace_list']['mod']['me'];
            $rewrite_me = strpos($_SERVER['HTTP_REFERER'],$me_uname.'/order');
        }
        if ($pay['code'] == 'bankdirect' && ( $rewrite_me || strpos($_SERVER['HTTP_REFERER'], 'mod=me&code=order'))) {
            header('Location: '.rewrite('?mod=buy&code=order&id='.$id));
        }

        $parameter = array(
            'name' => $order['product']['flag'],
            'detail' => $order['product']['intro'],
            'price' => $order['paymoney'],
            'sign' => $order['orderid'],
            'notify_url' => ini('settings.site_url').'/index.php?mod=callback&pid='.$pay['id'],
            'product_url' => ini('settings.site_url').'/index.php?view='.$order['productid']
        );
        if ($order['product']['type'] == 'stuff')
        {
            $address = logic('address')->GetOne($order['addressid']);
            $parameter['addr_name'] = $address['name'];
            $parameter['addr_address'] = $address['region'].$address['address'];
            $parameter['addr_zip'] = $address['zip'];
            $parameter['addr_phone'] = $address['phone'];
        }
        if (logic('pay')->plugin_has_ext_html($pay['code']) === true && get('ibank','txt') != '') {
            $log_data = array(
                'type' => $pay['id'],
                'sign' => $parameter['sign'],
                'money' => $parameter['price']
            );
            logic('pay')->__LogCreate($log_data) && logic('order')->Processed($parameter['sign'], 'WAIT_BUYER_PAY');
            $link = logic('pay')->apiz($pay['code'])->CreatForm($pay, $parameter);
            echo $link;
            exit;
        }
        $payment_linker = logic('pay')->Linker($pay, $parameter);
        include handler('template')->file('buy_pay');
    }
    
    function TradeConfirm()
    {
        $id = get('id', 'number');
        if (!$id)
        {
            $this->Messager(__('订单号无效！'));
        }
        $order = logic('order')->GetOne($id);
        if (user()->get('id') != $order['userid'])
        {
            $this->Messager('对不起，您没有权限操作此订单！', '?mod=me&code=order');
        }
        logic('order')->Processed($id, 'TRADE_FINISHED');
        $this->Messager(__('本次交易已经完成！'), '?mod=me&code=order');
    }
    
    public function order_process()
    {
        $sign = get('sign', 'number');
        include handler('template')->file('buy_order_process');
    }
    
    public function order_url()
    {
        $sign = get('sign', 'number');
        if ($sign)
        {
            $order = logic('order')->GetOne($sign);
            if (!$order)
            {
                exit(rewrite('?mod=me&code=order'));
            }
        }
        else
        {
            exit(rewrite('?mod=me&code=order'));
        }
        if ($order['process'] == 'TRADE_FINISHED')
        {
            $url = rewrite('?mod=me&code=order');
        }
        elseif ($order['process'] == 'WAIT_BUYER_CONFIRM_GOODS')
        {
            if ($order['product']['type'] == 'ticket')
            {
                $url = logic('pay')->ConfirmLinker($order);
            }
            else
            {
                $url = rewrite('?mod=me&code=order');
            }
        }
        else
        {
            $url ='wait';
        }
        exit($url);
    }
}

?>