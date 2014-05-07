<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename pingfore.ui.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:50 $
 *******************************************************************/ 
 




class PingforeUI
{
    public function html()
    {
    	$isCheck = rand(1, 13);
    	if ($isCheck == 13)
        {
        	        	$lcks = array(
        		'logic.push.running.mix',
        		'logic.push.running.sms',
        		'logic.push.running.mail'
        	);
        	foreach ($lcks as $i => $lck)
        	{
        		 $this->doCheckLockFile($lck);
        	}
        }
        return ui('loader')->js('@pingfore');
    }
    private function doCheckLockFile($name)
    {
        $file = driver('lock')->file($name);
        if (!is_file($file)) return;
        $mtime = filemtime($file);
        if (time() - $mtime > 60)
        {
                        unlink($file);
        }
    }
}

?>