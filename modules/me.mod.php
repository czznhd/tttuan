<?php

/**
 * 模块：个人操作
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name me.mod.php
 * @version 1.0
 */

class ModuleObject extends MasterObject
{
    var $city;
    var $config;
    function ModuleObject( $config )
    {
        $this->MasterObject($config);         if (MEMBER_ID < 1)
        {
            $this->Messager(__('请先登录！'), '?mod=account&code=login');
        }
        Load::logic('product');
        $this->ProductLogic = new ProductLogic();
        Load::logic('pay');
        $this->PayLogic = new PayLogic();
        Load::logic('me');
        $this->MeLogic = new MeLogic();
        Load::logic('order');
        $this->OrderLogic = new OrderLogic();
        $this->config = $config;
        $this->ID = ( int )($this->Post['id'] ? $this->Post['id'] : $this->Get['id']);
        $this->CacheConfig = ConfigHandler::get('cache');         $this->ShowConfig = ConfigHandler::get('show');         $runCode = Load::moduleCode($this, $this->Code);
        $this->$runCode();
    }
    function Main()
    {
        $_GET['code'] = 'coupon';
        $this->Coupon();
    }
    function Coupon()
    {
        $this->Title = __('我的团购券');
        $status = $this->Get['status'];
        if ($status == '')
        {
            $status = -1;
        }
        else
        {
            $status = (int)$status;
        }
        $ticket_all = logic('coupon')->GetList(user()->get('id'), ORD_ID_ANY, $status);
        
                $_s1=$_s2=$_s3= $_s4=3;
        if($status==-1) $_s1=2;
        if($status== 0) $_s2=2;
        if($status== 1) $_s3=2;
        if($status== 2) $_s4=2;
        
        include handler('template')->file('my_coupon');
    }
    
    function Order()
    {
        $this->Title = __('我的订单');
        $pay = $this->Get['pay'];
        if ($pay == '')
        {
            $pay = -1;
        }
        else
        {
            $pay = (int)$pay;
        }
        $order_all = logic('order')->GetList(user()->get('id'), -1, $pay);
        
                $_s1= $_s2 = $_s3=3;
        if($pay==-1) $_s1=2;
        if($pay==1 ) $_s2=2;
        if($pay==0 ) $_s3=2;
        
        include handler('template')->file('my_order');
    }
    
    function Bill()
    {
        $this->Title = __('消费详单');
        $usermoney = logic('me')->money()->log();
        include handler('template')->file('my_bill');
    }
    
    function Setting()
    {
        $this->Title = __('账户设置');
        $user = user()->get();
        include handler('template')->file('my_setting');
    }
    
    function Address()
    {
        $this->Title = __('收货地址');
        $addressList = logic('address')->GetList(user()->get('id'));
        include handler('template')->file('my_address');
    }
    
    
    function Cancel()
    {
                extract($this->Get);
        $this->OrderLogic->orderType($orderid, '0');
        $this->Messager("您已经成功取消该订单！", "?mod=me&code=order");
    }
    function Doinfo()
    {
        extract($this->Post);
        $ary = array();
        if ( $newpwd == $confirmpwd && $newpwd != '' )
        {
            $ary['password'] = md5($newpwd);
            
                        if ( true === UCENTER )
            {
                include_once (UC_CLIENT_ROOT . './client.php');
                $result = uc_user_edit(MEMBER_NAME, '', $newpwd, '', 1);
                if ( $result != 1 )
                {
                    $this->Messager('通知UC修改密码失败，请检查你的UC配置！');
                }
            }
            

                        $loginSource = dbc(DBCMax)->select('members')->where(array('uid'=>MEMBER_ID))->limit(1)->done();
            $from = explode('.', $loginSource['email2']);
            
            if ($from[1] == 'qq') {
                $ary['password'] = md5($ary['password']);
                account('ulogin')->UserPasswdQQ(user()->get('id'), $confirmpwd, $loginSource['email2']);
            }else{
                account('ulogin')->UserPasswd(user()->get('id'), $newpwd);
            }

            if ($loginSource['email2'] == 'zuitu') {
                $ary['password'] = md5($newpwd.'@4!@#$%@');
            }
        }

        if ( $phone != '' )
        {
            if (is_numeric($phone))
            {
                $ary['phone'] = $phone;
            }
            else
            {
                $this->Messager('手机号码输入不正确，请重新输入！');
            }
        };
        $sql = 'select `email` from ' . TABLE_PREFIX . 'system_members where uid = ' . MEMBER_ID;
        $query = $this->DatabaseHandler->Query($sql);
        $user = $query->GetRow();
                        if ($qq == '' || is_numeric($qq))
        {
            $ary['qq'] = $qq;
        }
        else
        {
            $this->Messager('QQ号码输入不正确，请重新输入！');
        }
        if ( $user['email'] != $email )
        {
            if (check_email($email))
            {
                $ary['email'] = $email;
            }
            else
            {
                $this->Messager('邮件地址不正确，请重新输入！');
            }
            if ( $this->config['default_emailcheck'] )
            {
                $ary['checked'] = 0;
            }
            if (logic('account')->Exists('mail', $email))
            {
                return $this->Messager('Email 地址已经被使用！');
            }
        }
        
        $this->DatabaseHandler->SetTable(TABLE_PREFIX . 'system_members');
        $result = $this->DatabaseHandler->Update($ary, 'uid = ' . MEMBER_ID);

                if ($from[1] == 'qq') {
            account()->login(MEMBER_NAME, md5($newpwd), true);
        }

        $this->Messager("资料修改成功！", "?mod=me&code=setting");
    }
    function Printticket()
    {
        extract($this->Get);
        $order = $this->OrderLogic->GetTicket($id);
        $pwd = $order['password'];
        if ( $order == '' || $pwd == '' ) $this->Messager("读取团购券出现错误！", "?mod=me");
        include ($this->TemplateHandler->Template("tttuangou_printticket"));
    }
    
    function Addmoney()
    {
		exit();
        $money = $this->MeLogic->moneyMe();
        $pay = $this->PayLogic->payType(intval($id), $this->city);
        $action = '?mod=me&code=doaddmoney';
        include ($this->TemplateHandler->Template("tttuangou_addmoney"));
    }
    function Topay( $mod, $returnurl, $pay )
    {
		exit();
        $payment = unserialize($pay['pay_config']);
        $returnurl .= '&pay=' . $mod;
        include_once ('./modules/' . $mod . '.pay.php');
        $bottom = payTo($payment, $returnurl, $pay);
        return $bottom;
    }
    function Doaddmoney()
    {
		exit();
        $this->Post['money'] = round($this->Post['money'], 2);
        if ( $this->Post['paytype'] == '' ) $this->Messager("您没有选择支付方式或者没有适合的支付接口！");
        if ( ! is_numeric($this->Post['money']) || $this->Post['money'] <= 0 ) $this->Messager("充值金额必须大于0！");
        $pay = $this->PayLogic->payChoose(intval($this->Post['paytype']));
        $pay['orderid'] = time() . rand(10000, 99999);
        $pay['price'] = $this->Post['money'];
        $pay['name'] = '账户充值';
        $pay['show_url'] = $this->Config['site_url'] . '/?mod=me&code=money';
        $returnurl = $this->Config['site_url'] . '/index.php?mod=me&code=readdmoney';
        $bottom = $this->Topay($pay['pay_code'], $returnurl, $pay);
        include ($this->TemplateHandler->Template('tttuangou_doaddmoney'));
    }
    function Readdmoney()
    {
		exit();
        $pay_code = (isset($_POST['pay']) ? $_POST['pay'] : $_GET['pay']);
        if ( $pay_code == '' )
        {
            $this->Messager('参数传递错误！');
        }
        if ( isset($_GET['pay']) )
        {
            $is_notify = false;
            $userID = MEMBER_ID;
        }
        elseif ( isset($_POST['pay']) )
        {
            $is_notify = true;
            $userID = $_POST['uid'];
        }
        include_once ('./modules/' . $pay_code . '.pay.php');
        $msg = addmymoney($userID);
        $oid = isset($_GET['out_trade_no']) ? $_GET['out_trade_no'] : $_POST['out_trade_no'];
        $trade_status = isset($_GET['trade_status']) ? $_GET['trade_status'] : $_POST['trade_status'];
        $pay = $this->PayLogic->payChoose($pay_code);
        $pay = unserialize($pay['pay_config']);
        $ifTrust = true;
        if ( $pay['alipay_iftype'] == 'distrust' )
        {
            $ifTrust = false;
        }
        if ( is_array($msg) )
        {
            if ( $is_notify )
            {
                $trade_no = $_POST['trade_no'];
                if ( $ifTrust || $trade_status == 'TRADE_FINISHED' )
                {
                                        $this->Dopay($msg['price'], $msg['orderid'], $userID, $trade_no);
                }
                if ( ! $ifTrust && $trade_status == 'WAIT_SELLER_SEND_GOODS' )
                {
                                        $url = sendGoods($trade_no, $pay);
                    $doc = new DOMDocument();
                    $doc->load($url);
                                     }
                exit('success');
            }
            if ( $pay_code != 'alipay' )
            {
                if ( $pay_code == 'tenpay' )
                {
                    $trade_no = $_REQUEST['transaction_id'];
                }
                elseif ( $pay_code == 'kuaiqian' )
                {
                    $trade_no = $_REQUEST['dealId'];
                }
                $result = $this->Dopay($msg['price'], $msg['orderid'], $userID, $trade_no);
                $this->Messager($result, '?mod=me&code=money');
            }
            else
            {
                if ( ! $ifTrust && $trade_status != 'TRADE_FINISHED' )
                {
                                        $this->Messager('支付还没有完成，请您先确认收货，之后系统便会自动完成本次交易！', 'http:/' . '/lab.alipay.com/consume/record/buyerConfirmTrade.htm?tradeNo=' . $_GET['trade_no'], 5);
                }
            }
            $this->Messager('充值成功！', '?mod=me&code=money');
        }
        else
        {
            if ( $is_notify )
            {
                exit('success');
            }
                        if ( $pay_code == 'alipay' && $msg == '您不能重复充值同一订单，充值失败！' )
            {
                $this->Messager('充值成功！', '?mod=me&code=money');
            }
            $this->Messager($msg, '?mod=me&code=money');
        }
        ;
    }
    function Dopay( $price, $orderid, $userID, $trade_no )
    {
		exit();
        if ( $price == '' || $orderid == '' )
        {
            return "支付失败!";
        }
        ;
                if ( $price > 0 )
        {
            $pay = $this->MeLogic->moneyAdd($price, $userID);
            $ary = array( 
                'userid' => $userID, 'type' => 1, 'name' => '直接充值', 'intro' => '您为账户充值' . $price . '元', 'money' => $price, 'time' => time(), 'trade_no' => $trade_no 
            );
            $this->MeLogic->moneyLog($ary);
        }
        ;
                $ary = array( 
            'id' => $orderid, 'money' => $price, 'userid' => $userID, 'paytime' => date('Y-m-d H:i:s') 
        );
        $this->DatabaseHandler->SetTable(TABLE_PREFIX . 'tttuangou_addmoney');
        $result = $this->DatabaseHandler->Insert($ary);
        return '充值成功！';
    }
    
    function Face()
    {
		exit();
        $sql = 'select ucuid from ' . TABLE_PREFIX . 'system_members where uid = ' . MEMBER_ID;
        $query = $this->DatabaseHandler->Query($sql);
        $usr = $query->GetRow();
        if ( UCENTER )
        {
            include_once (UC_CLIENT_ROOT . './client.php');
            $face = uc_avatar($usr['ucuid']);
        }
        else
        {
            ;
        }
        include ($this->TemplateHandler->Template("tttuangou_face"));
    }
    
        
    function __AddressCheckOns( $id )
    {
        return false;
        $sql = 'SELECT COUNT(orderid) AS CNT FROM ' . TABLE_PREFIX . 'tttuangou_order WHERE addressid=' . $id . ' AND status IN(1,4)';
        $query = $this->DatabaseHandler->Query($sql)->GetRow();
        if ( $query['CNT'] > 0 )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>