<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename getseller.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:50 $
 *******************************************************************/ 
 

class ModuleObject extends MasterObject
{
	var $Config = array(); 	var $ID;

	function ModuleObject(& $config){
		$this->MasterObject($config);
		$this->initMemberHandler();
		$this->ID=$this->Post['id']?(int)$this->Post['id']:(int)$this->Get['id'];
		Load::moduleCode($this);$this->Execute();
	}

	function Execute(){
		$this->Showseller();
	}

	function Showseller(){
		$id=$this->Get['city'];
		$sql='SELECT * FROM '.TABLE_PREFIX.'tttuangou_seller where area = '.intval($id);
		$query = dbc()->Query($sql); 
		$seller=$query->GetAll();
		if(empty($seller)){echo __('暂无商家');exit;}
		echo '<select name="sellerid" id="sellerid">';
		foreach($seller as $i => $value){
			echo '<option value="'.$value['id'].'"';
			if ($_GET['seller'] == $value['id'])
			{
			    echo ' selected="selected"';
			}
			echo '>'.$value['sellername'].'</option>';
		}
		echo ' </select>';	
		exit;
	}
}
?>