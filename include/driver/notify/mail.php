<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename mail.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:50 $
 *******************************************************************/ 
 



class mailNotifyDriver extends NotifyDriver
{
    private $conf = array();
    function __construct($conf)
    {
        $this->conf = $conf;
    }
    
    public function __default($class, $uid, $data)
    {
        if (!is_numeric($uid)) return;
        $email = user($uid)->get('email');
        if (!preg_match('/[a-z0-9\._]+@[a-z0-9\.-]+/', $email)) return;
        $msg = ini('notify.event.'.$class.'.msg.mail');
        if (!$msg) return false;
        
        $this->FlagParser($class.'.mail', $data, $msg);
        logic('push')->add('mail', $email, array(
            'subject' => ini('settings.site_name').' 提示您',
            'content' => $msg
        ), 6);
        return 'QUEUE RECEIVED';
    }
}

?>