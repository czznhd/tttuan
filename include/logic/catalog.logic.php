<?php

/**
 * 逻辑区：产品分类目录
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package logic
 * @name catalog.logic.php
 * @version 1.0
 */

class CatalogLogic
{
	public $urlTopClass = false;
	private $cacheKEY = 'catalog.logic.procount.update';
	
	private function ProCountSync()
	{
		$lastUpdate = fcache($this->cacheKEY, dfTimer('com.catalog.procount.sync'));
		if (!$lastUpdate)
		{
			$topClasses = $this->GetList();
			foreach ($topClasses as $i => $topClass)
			{
				$subClasses = $this->GetList($topClass['id']);
				if (!$subClasses) continue;
				foreach ($subClasses as $ii => $subClass)
				{
															$r = dbc(DBCMax)->select('product')->in('COUNT(1) AS procount')->where('category='.$subClass['id'])->limit(1)->done();
										dbc(DBCMax)->update('catalog')->data('procount='.$r['procount'])->where('id='.$subClass['id'])->done();
										$r = dbc(DBCMax)->select('product')->in('COUNT(1) AS oslcount')->where('category='.$subClass['id'].' AND (status='.PRO_STA_Normal.' OR status='.PRO_STA_Success.')')->limit(1)->done();
										dbc(DBCMax)->update('catalog')->data('oslcount='.$r['oslcount'])->where('id='.$subClass['id'])->done();
				}
			}
			fcache($this->cacheKEY, time());
		}
	}
	public function Enabled()
	{
		return ini('catalog.enabled');
	}
	public function FilterEnabled()
	{
		return ini('catalog.filter.empty.enabled');
	}
	
	public function Navigate()
	{
		$class = $this->GetList(0);
		if (!$class) return array();
		$class = $this->front_formatted($class);
		foreach ($class as $i => $topclass)
		{
			if ($topclass['id'] > 0)
			{
				$class[$i]['subclass'] = $this->GetList($topclass['id']);
				$topsd = false;
				$class[$i]['subclass'] = $this->front_formatted($class[$i]['subclass'], $topclass['flag'], $topsd);
				if ($topsd)
				{
					$class[$i]['selected'] = true;
				}
			}
		}
		$this->ProCountSync();
		return $class;
	}
	
	private function front_formatted($list, $top_code = false, &$topsd = false)
	{
		if (is_array($list))
		{
			array_unshift($list, array('id' => 0, 'name' => '全部', 'flag' => ''));
			foreach ($list as $i => $one)
			{
				$code = $top_code ? ($top_code.(($one['flag'] ? '_' : '').$one['flag'])) : $one['flag'];
				if ($_GET['code'] == $code)
				{
					$list[$i]['selected'] = true;
					$topsd = true;
				}
				$list[$i]['url'] = logic('url')->create('catalog', array('code' => $code));
			}
		}
		return $list;
	}
	
	public function GetOne($id)
	{
		return dbc(DBCMax)->select('catalog')->where('id='.$id)->limit(1)->done();
	}
	
	public function GetList($parent = 0)
	{
		return dbc(DBCMax)->select('catalog')->where('parent='.$parent)->order('`order`.asc')->done();
	}
	
	public function Search($where, $limit = 1)
	{
		$dbo = dbc(DBCMax)->select('catalog')->where($where);
		$limit && $dbo->limit($limit);
		return $dbo->done();
	}
	
	public function Filter($catalog)
	{
		$catalog == 'main' && $catalog = '_';
		list($topclass, $subclass) = explode('_', $catalog);
				if ($topclass && $subclass)
		{
			return $this->Filter_subClass($subclass);
		}
		elseif ($topclass)
		{
			return $this->Filter_topClass($topclass);
		}
		else
		{
			return '1';
		}
	}
	
	private function Filter_subClass($classFlag)
	{
		$subClass = $this->Search(array('flag'=>$classFlag));
		if ($subClass['id'] > 0)
		{
			$this->urlTopClass = $subClass['parent'];
			return 'category = '.$subClass['id'];
		}
		else
		{
			return '0';
		}
	}
	
	private function Filter_topClass($classFlag)
	{
		$topClass = $this->Search(array('flag'=>$classFlag));
		$this->urlTopClass = $topClassID = $topClass['id'];
				$subClasses = (array)$this->Search(array('parent'=>$topClassID), 0);
		$sIDS = '';
		foreach ($subClasses as $i => $subClass)
		{
			$sIDS .= $subClass['id'].',';
		}
		$sIDS = substr($sIDS, 0, -1);
		if ($sIDS)
		{
			return 'category IN('.$sIDS.')';
		}
		else
		{
			return '0';
		}
	}
	
	public function Add($parent, $flag, $name)
	{
				$checked = $this->Search(array('flag'=>$flag));
		if ($checked)
		{
			return -1;
		}
		return dbc(DBCMax)->insert('catalog')->data(array(
			'parent' => $parent,
			'name' => $name,
			'flag' => $flag,
			'procount' => 0,
			'upstime' => time()
		))->done();
	}
	
	private function Delete_where($where)
	{
		return dbc(DBCMax)->delete('catalog')->where($where)->done();
	}
	
	public function Delete($id)
	{
				$catalog = $this->Search('id='.$id);
		if (!$catalog) return false;
		$master = false;
		if ($catalog['parent'] == 0)
		{
			$master = true;
		}
		$pro_where = 'category = '.$id;
				$this->Delete_where('id='.$id);
				if ($master)
		{
			$sublist = $this->GetList($id);
			$this->Delete_where('parent='.$id);
			if ($sublist)
			{
				$pro_where = 'category IN(';
				foreach ($sublist as $i => $one)
				{
					$pro_where .= $one['id'].',';
				}
				$pro_where = substr($pro_where, 0, -1).')';
			}            
		}
				dbc()->Query('UPDATE '.table('product').' SET category=0 WHERE '.$pro_where);
		return true;
	}
	
	public function ProUpdate(&$data = false)
	{
		if ($data)
		{
			$cid_old = post('__catalog_subclass_old', 'int');
			$cid_new = post('__catalog_subclass', 'int');
			if ($cid_old == $cid_new) return;
			$data['category'] = $cid_new;
		}
				fcache($this->cacheKEY, 0);
	}
}

?>
