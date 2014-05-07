<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename catalog.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:51 $
 *******************************************************************/ 
 




class ModuleObject extends MasterObject
{
    public $Title = '';
    function ModuleObject( $config )
    {
        $this->MasterObject($config);
                $runCode = Load::moduleCode($this, false, false);
        $this->Sort($runCode);
    }
    private function Sort($catalog)
    {
        $data = logic('product')->display(logic('catalog')->Filter($catalog));
                if (!$data)
        {
            $data = array('product'=>array(),'mutiView'=>true);
        }
        else
        {
                        $data['mutiView'] = true;
            $product = (isset($data['product']['id']) && $data['product']['id']>0) ? array($data['product']) : $data['product'];
        }
        $this->Title = $data['mutiView'] ? '' : $product['name'];
                include handler('template')->file('home');
    }
}

?>
