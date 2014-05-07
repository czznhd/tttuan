<?php

/**
 * 逻辑区：城市区域管理
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package logic
 * @name city.logic.php
 * @version 1.0
 */

class CityManageLogic
{
	
	public function place_enabled()
	{
		return ini('cplace.enabled');
	}
	
	public function place_navigate()
	{
		if ($this->place_enabled())
		{
			$cityID = logic('misc')->City('id');
			$places = $this->get_places($cityID);
			$regionID = get('region', 'int');
			$streetID = get('street', 'int');
			$sel_filter = array();
			$sel_filter[] = 'region:'.$regionID;
			$sel_filter[] = 'street:'.$streetID;
			$places = $this->make_default_data($places);
			$places = $this->make_selected($places, $sel_filter);
			$places = $this->make_links($places);
			include handler('template')->file('city_place_navigate');
		}
	}
	
	public function place_inputer($product_id)
	{
		$places = array();
		if ($product_id)
		{
			$product = dbc(DBCMax)->select('product')->where(array('id' => $product_id))->limit(1)->done();
			$places = $this->get_places($product['city']);
			$places = $this->make_selected($places, array('region:'.$product['city_place_region'], 'street:'.$product['city_place_street']));
		}
		include handler('template')->file('@admin/city_place_inputer');
	}
	
	private function make_default_data($places)
	{
		$places[0] = array('id' => 0, 'type' => 'region', 'name' => '全部');
				ksort($places);
				foreach ($places as $i => $region)
		{
			if (isset($places[$i]['streets']))
			{
				$places[$i]['streets'][0] = array('id' => 0, 'type' => 'street', 'pareny_type' => 'region', 'parent_id' => $region['id'], 'name' => '全部');
								ksort($places[$i]['streets']);
			}
		}
		return $places;
	}
	
	private function make_links($places)
	{
		foreach ($places as $i => $region)
		{
			$places[$i]['url'] = logic('url')->create('product', array('region' => $region['id'], 'street' => null));
			if (isset($region['streets']))
			{
				foreach ($region['streets'] as $ii => $street)
				{
					$places[$i]['streets'][$ii]['url'] = logic('url')->create('product', array('street' => $street['id']));
				}
			}
		}
		return $places;
	}
	
	private function make_selected($places, $filter)
	{
		$region_sels = array();
		$region_ix_base = 0;
		foreach ($filter as $fone)
		{
			list($type, $id) = explode(':', $fone);
			if ($type == 'street')
			{
				if ($id > 0)
				{
					$street_data = $this->get_place_one($id);
				}
				else
				{
					$street_data = array('parent_id' => get('region', 'int'), 'id' => 0);
				}
				$street_region = $this->get_place_one($street_data['parent_id']);
				if (isset($places[$street_region['id']]['streets'][$street_data['id']]))
				{
					$places[$street_region['id']]['streets'][$street_data['id']]['selected'] = true;
					$places[$street_region['id']]['selected'] = true;
					$region_sels[$street_region['id']] += ($region_ix_base += 2);
				}
			}
			if ($type == 'region')
			{
				if (isset($places[$id]))
				{
					$places[$id]['selected'] = true;
					$region_sels[$id] += ($region_ix_base += 1);
				}
			}
		}
		if (count($region_sels) > 1)
		{
			asort($region_sels);
			array_pop($region_sels); 			foreach ($region_sels as $id => $ix)
			{
				if ($ix) unset($places[$id]['selected']);
			}
		}
		return $places;
	}
	
	public function get_places($cityId)
	{
		$regions = $this->get_of_parent('city', $cityId);
		foreach ($regions as $i => $region)
		{
			$regions[$i]['streets'] = $this->get_of_parent('region', $region['id']);
		}
		return $regions;
	}
	
	public function get_place_one($id)
	{
		return dbc(DBCMax)->select('city_place')->where(array('id' => $id))->limit(1)->done();
	}
	
	public function add_place($type, $id, $name)
	{
		$maps = array(
				'city' => 'region',
				'region' => 'street'
			);
		$placeType = isset($maps[$type]) ? $maps[$type] : 'null';
		$result = dbc(DBCMax)->insert('city_place')->data(array(
				'type' => $placeType,
				'parent_type' => $type,
				'parent_id' => $id,
				'name' => $name,
				'timestamp_update' => time()
			))->done();
		return $result > 0 ? $result : -1;
	}
	
	public function del_place($id)
	{
		$old = $this->get_place_one($id);
		if ($old)
		{
			if ($old['type'] != 'street')
			{
				dbc(DBCMax)->delete('city_place')->where(array('parent_type' => $old['type'], 'parent_id' => $old['id']))->done();
			}
			$this->product_place_clear($old['type'], $id);
			return dbc(DBCMax)->delete('city_place')->where(array('id' => $id))->done();
		}
		else
		{
			return false;
		}
	}
	
	public function get_of_parent($type, $id)
	{
		$datas = dbc(DBCMax)->select('city_place')->where(array('parent_type' => $type, 'parent_id' => $id))->done();
		$datas || $datas = array();
		$returns = array();
		foreach ($datas as $data)
		{
			$returns[$data['id']] = $data;
		}
		return $returns;
	}
	
	private function product_place_clear($type, $id)
	{
		if ($type == 'region')
		{
			$field = 'city_place_region';
			$data = array(
				'city_place_region' => 0,
				'city_place_street' => 0
			);
		}
		else
		{
			$field = 'city_place_'.$type;
			$data = array(
				'city_place_'.$type => 0
			);
		}
		dbc(DBCMax)->update('product')->data($data)->where(array($field => $id))->done();
	}
	
	public function product_on_save(&$data)
	{
		if ($data && $this->place_enabled())
		{
			$data['city_place_region'] = post('__cplace_region', 'int');
			$data['city_place_street'] = post('__cplace_street', 'int');
		}
	}
	
	public function place_sql_filter()
	{
		$street = get('street', 'int');
		$region = get('region', 'int');
		if ($street)
		{
			get('page', 'int') > 0 || $_GET['page'] = 1;
			$region = $this->get_place_one($street);
			if ($region)
			{
				return '(p.city_place_street = '.$street.' or (p.city_place_region = '.$region['parent_id'].' and p.city_place_street = 0))';
			}
			else
			{
				return '0';
			}
		}
		elseif ($region)
		{
			get('page', 'int') > 0 || $_GET['page'] = 1;
			return '(p.city_place_region = '.$region.' or p.city_place_region = 0)';
		}
		else
		{
			return '1';
		}
	}
}

?>