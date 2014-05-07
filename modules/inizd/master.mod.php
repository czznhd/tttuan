<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename master.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:51 $
 *******************************************************************/ 
 



class MasterObject
{
    var $Module='index';
    var $Code='';
    var $FILE='inizd';
    var $OPC = '';
    function MasterObject(&$config)
    {
        $this->Get  =  &$_GET;
		$this->Post =  &$_POST;
        $this->Module = trim($this->Post['mod']?$this->Post['mod']:$this->Get['mod']);
        $this->Code = trim($this->Post['code']?$this->Post['code']:$this->Get['code']);
		$this->OPC = trim($this->Post['op']?$this->Post['op']:$this->Get['op']);
    }
}


?>