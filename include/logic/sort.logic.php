<?php

/**
 * 逻辑区：排序管理
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package logic
 * @name sort.logic.php
 * @version 1.0
 */

class SortManageLogic
{
	
	private $filterPools = array(
		'default' => array(
			'name' => '默认',
			'sql' => 'p.order DESC, p.id DESC'
		),
		'recent' => array(
			'name' => '最新',
			'title' => '按照上架时间显示最新',
			'dir' => 'down',
			'sql' => 'p.begintime DESC'
		),
		'sells' => array(
			'name' => '销量',
			'title' => '按照产品销量显示热卖产品',
			'dir' => 'down',
			'sql' => 'p.sells_count+p.virtualnum DESC'
		),
		'price-asc' => array(
			'name' => '价格(最低)',
			'title' => '按照价格从低到高显示',
			'dir' => 'up',
			'sql' => 'p.nowprice ASC'
		),
		'price-desc' => array(
			'name' => '价格(最高)',
			'title' => '按照价格从高到低显示',
			'dir' => 'down',
			'sql' => 'p.nowprice DESC'
		)
	);
	
	public function filter_pools()
	{
		return $this->filterPools;
	}
	
	public function set_filter_pool($key, $config)
	{
		$this->filterPools[$key] = $config;
	}
	
	public function product_navigate()
	{
		$sortKey = $this->get_sort_key();
		$sorts = $this->filterPools;
		foreach ($sorts as $k => $data)
		{
			$sorts[$k]['url'] = logic('url')->create('product', array('sort' => $k));
			if ($sortKey == $k)
			{
				$sorts[$k]['selected'] = true;
			}
		}
		include handler('template')->file('home_sort_navigate');
	}
	
	public function product_sql_filter()
	{
		return $this->filterPools[$this->get_sort_key()]['sql'];
	}
	
	private function get_sort_key()
	{
		$sk = get('sort', 'string');
		return isset($this->filterPools[$sk]) ? $sk : 'default';
	}
}

?>