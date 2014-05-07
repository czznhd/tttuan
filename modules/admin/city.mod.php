<?php

/**
 * 模块：城市管理
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name city.mod.php
 * @version 1.0
 */

class ModuleObject extends MasterObject
{
	function ModuleObject( $config )
	{
		$this->MasterObject($config);
		$runCode = Load::moduleCode($this);
		$this->$runCode();
	}
	function main()
	{
		header('Location admin.php?mod=tttuangou&code=city');
	}
	
	public function place()
	{
		$cityId = get('cid', 'int');
		$city = dbc(DBCMax)->select('city')->where(array('cityid' => $cityId))->limit(1)->done();
		$places = logic('city')->get_places($cityId);
		include handler('template')->file('@admin/city_place_list');
	}
	
	public function place_add()
	{
		$parenttype = get('parenttype', 'string');
		$parentid = get('parentid', 'int');
		include handler('template')->file('@admin/city_place_add');
	}
	public function place_save()
	{
		$parenttype = post('parenttype', 'string');
		$parentid = post('parentid', 'int');
		$name = post('name', 'text');
		$id = logic('city')->add_place($parenttype, $parentid, $name);
		exit('<script type="text/javascript">var op = window.opener ? window.opener : window.parent; op.__cplace_add_finish('.$id.');</script>');
	}
	
	public function place_del()
	{
		$id = get('id', 'int');
		$id || exit('false');
		logic('city')->del_place($id);
		exit('ok');
	}
	
	public function place_ajaxlist()
	{
		$type = get('type', 'string');
		$id = get('id', 'int');
		$datas = logic('city')->get_of_parent($type, $id);
		$html = '<option value="0">全部</option>';
		foreach ($datas as $data)
		{
			$html .= '<option value="'.$data['id'].'">'.$data['name'].'</option>';
		}
		exit($html);
	}
}

?>