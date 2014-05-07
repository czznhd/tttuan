<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename payment.drv.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:50 $
 *******************************************************************/ 
 




class PaymentDriver
{
    
    public final function load($name)
    {
        $file = dirname(__FILE__).'/payment/'.$name.'.php';
	    include_once $file;
	    $className = $name.'PaymentDriver';
	    return new $className();
    }
    
    public function CreateLink($payment, $parameter)
    {
        
    }
    
    public function CreateConfirmLink($payment, $order)
    {
        
    }
    
    public function CallbackVerify($payment)
    {
        
    }
    
    public function GetTradeData()
    {
        
    }
    
    public function StatusProcesser($status)
    {
        
    }
    
    public function GoodSender($payment, $express, $sign, $type)
    {
    	
    }
}

?>