<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename upload.mod.php $
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
    function Config()
    {
        $upcfg = ini('upload');
        list($size_unit, $size_val) = explode(':', $upcfg['size']);
        $sys_roles = logic('me')->role()->GetList();
        $sel_roles = explode(',', $upcfg['role']);
        include handler('template')->file('@admin/upload_config');
    }
    function Config_save()
    {
        $exts = post('exts', 'text');
        $size_unit = post('size_unit', 'text');
        $size_val = post('size_val', 'int');
        $roles = post('role');
        $upcfg = array(
            'exts' => $exts,
            'size' => $size_unit.':'.$size_val,
            'role' => implode(',', $roles)
        );
        ini('upload', $upcfg);
        $this->Messager('保存成功！');
    }
}

?>