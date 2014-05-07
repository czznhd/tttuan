<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename rbac.logic.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:50 $
 *******************************************************************/ 
 




class RBACLogic
{
    
    public function Access($file, $module, $action)
    {
        $idx = 'rbac.list.'.$file.'.'.$module.'.'.$action;
                $action = ini($idx);
        if (!$action)
        {
            ini($idx, array(
                'name' => '',
                'enabled' => false,
            ));
            return;
        }
        if (!$action['enabled'])
        {
            return;
        }
                if (user()->get('id') != 1)
        {
			$text = '抱歉，演示帐号不可以访问此功能！';
			include handler('template')->file('@inizd/alert');
            exit();
        }
    }
}

?>