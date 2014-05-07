<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename ui.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:50 $
 *******************************************************************/ 
 




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
        header('Location: ?mod=ui&code=igos&op=config');
    }
    function iGOS_config()
    {
        include handler('template')->file('@admin/ui_igos_config');
    }
    function iGOS_save()
    {
        $style = post('style', 'txt');
        ini('ui.igos.style', $style);
        $this->Messager(__('保存成功！'));
    }
}
?>