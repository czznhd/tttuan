<?php

/**
 * 界面支持：分类导航
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package UserInterface
 * @name catalog.ui.php
 * @version 1.0
 */

class CatalogUI
{
    
    public function display()
    {
        if (!logic('catalog')->Enabled()) return;
                $catalog = logic('catalog')->Navigate();
                if (logic('catalog')->FilterEnabled())
        {
            foreach ($catalog as $_i => $_topclass)
            {
                if (isset($_topclass['oslcount']))
                {
                    $_topclass['subclass'] || $_topclass['subclass'] = array();
                    $subprocount = 0;
                    foreach ($_topclass['subclass'] as $_ii => $_subclass)
                    {
                        if (isset($_subclass['oslcount']))
                        {
                            if ($_subclass['oslcount'] == 0)
                            {
                                unset($_topclass['subclass'][$_ii]);
                                continue;
                            }
                            $subprocount += $_subclass['oslcount'];
                        }
                    }
                    if ($subprocount == 0)
                    {
                        unset($catalog[$_i]);
                    }
                    else
                    {
                        $catalog[$_i] = $_topclass;
                    }
                }
            }
        }
        include handler('template')->file('@html/catalog/navigate');
    }
    public function inputer($category)
    {
        $category || $category = 0;
        $category && $master = logic('catalog')->GetOne($category);
        $catalog = logic('catalog')->Navigate();
        include handler('template')->file('@html/catalog/inputer');
    }
}

?>