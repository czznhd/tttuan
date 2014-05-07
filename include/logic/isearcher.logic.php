<?php

/**
 * 
 * 逻辑区：智能搜索
 * 
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package logic
 * @name isearcher.logic.php
 * @version 1.0
 */

class iSearcherLogic
{
    
    function Search($fid, $wd)
    {
        $map = ini('isearcher.map.'.$fid);
        if (!$map)
        {
            return $this->__AJax_NOT_Found();
        }
        $table = $map['table'];
        $field = $map['src'];
        $iwField = $map['idx'];
        $exsql = $table == 'product' ? 'and saveHandler="normal" order by addtime desc' : '';
        $sql = 'SELECT '.$iwField.','.$field.' FROM '.table($table).' WHERE '.$field.' LIKE "%'.$wd.'%" '.$exsql.' LIMIT 0, 10';
        $query = dbc()->Query($sql);
        if (!$query)
        {
            return $this->__AJax_NOT_Found();
        }
        $result = $query->GetAll();
        if (count($result) == 0)
        {
            return $this->__AJax_NOT_Found();
        }
        $ops = array(
            'resultCount' => count($result)
        );
        foreach ($result as $i => $one)
        {
            $ops['results'][] = array(
                'title' => $one[$field],
                'key' => $map['key'],
                'val' => $one[$iwField]
            );
        }
        return $ops;
    }
    
    public function iiSearch($map, &$key, &$wd)
    {
        if ($key != 'wd')
        {
                        $wd = ' ='.$wd;
            return;
        }
                $key = $map['key'];
        $table = $map['table'];
        $field = $map['src'];
        $iwField = $map['idx'];
        $exsql = $table == 'product' ? 'and saveHandler="normal" order by addtime desc' : '';
        $sql = 'SELECT '.$iwField.','.$field.' FROM '.table($table).' WHERE '.$field.' LIKE "%'.$wd.'%" '.$exsql;
        $query = dbc()->Query($sql);
        if (!$query)
        {
            $key = '__404__';
            return;
        }
        $ids = $query->GetAll();
        if (count($ids) == 0)
        {
            $key = '__404__';
            return;
        }
        $idx = '';
        foreach ($ids as $i => $id)
        {
            $idx .= $id[$iwField].',';
        }
        $idx = substr($idx, 0, -1);
        $wd = ' IN('.$idx.')';
    }
    
    private function __AJax_NOT_Found()
    {
        return array(
            'resultCount' => 0,
            'msg' => __('没有找到相关内容！'),
        );
    }
    
    public function Linker(&$sql)
    {
        $swd = get('search','string');
        if ($swd)
        {
            list($key, $wd) = explode(':', $swd);
                                            }
        else
        {
            $key = false;
            $wd = false;
        }
        $ssrc = get('ssrc', 'txt');
        if ($ssrc)
		{
			$map = ini('isearcher.map.'.$ssrc);
		}
		else
		{
			$map = false;
		}
		$mocod = str_replace('.', '_', mocod());
		if ($key && $wd && $map)
		{
			$parser = '_lnk_of_'.$mocod;
			$this->$parser($sql, $key, $wd, $map);
		}
		$this->timev_invades($sql, $mocod, array(
			'order_vlist' => 'o.',
            'recharge_order' => ''
		));
    }
	
	public function product_sql_filter()
	{
		$kw = $this->get_kw();
		if ($kw)
		{
			get('page', 'int') > 0 || $_GET['page'] = 1;
			return '(p.flag like "%'.$kw.'%" or p.name like "%'.$kw.'%" or p.intro like "%'.$kw.'%")';
		}
		else
		{
			return '1';
		}
	}
	
	public function inputer()
	{
		$kw = $this->get_kw();
		include handler('template')->file('isearcher_inputer');
        return $kw;
	}
	
	private function get_kw($key = 'kw', $filter_rule = '/[\~\!\@\#\$\%\^\&\*\(\)\_\+\`\-\=\{\}\:\"\|\<\>\?\[\]\;\\\'\\\\\,\/]/')
	{
		$kw = get($key, 'string');
		if ($filter_rule)
		{
			$kw = preg_replace($filter_rule, '', $kw);
		}
        $ku = ENC_G2U($kw);
        $kg = ENC_U2G($kw);
        if ($kw == $ku)
        {
            $ic = 'utf8';
        }
        elseif ($kw == $kg)
        {
            $ic = 'gbk';
        }
        if (ENC_IS_GBK)
        {
            if ($ic == 'utf8')
            {
                $kw = $kg;
            }
        }
        else
        {
            if ($ic == 'gbk')
            {
                $kw = $ku;
            }
        }
		return $kw;
	}
	
	private function timev_invades(&$sql, $area, $prefixMaps = array())
	{
		if (isset($prefixMaps[$area]))
		{
			$prefix = $prefixMaps[$area];
			if (isset($_GET['iscp_tv_area']))
			{
				$area = get('iscp_tv_area', 'txt');
				$inskey = get('iscp_tvfield_'.$area, 'txt');
				if ($inskey)
				{
					$sd = array();
					$tvss = ini('isearcher.timev.'.$area);
					if ($tvss)
					{
						foreach ($tvss as $tvsd)
						{
							if ($tvsd['key'] == $inskey)
							{
								$sd = $tvsd;
								continue;
							}
						}
					}
					if ($sd)
					{
						$begin = get('iscp_tvbegin_'.$area, 'txt');
						$finish = get('iscp_tvfinish_'.$area, 'txt');
												$dbfield = $prefix.$sd['field'];
						$ts_begin = strtotime($begin);
						$ts_finish = strtotime($finish);
                        $ts_begin == $ts_finish && $ts_finish = $ts_begin + 86399 ;
                        
						if ($ts_begin || $ts_finish)
						{
							$ts = array();
							$ts_begin && $ts[] = $dbfield.' >= '.$ts_begin;
							$ts_finish && $ts[] = $dbfield.' <= '.$ts_finish;
							$sql_where = implode(' AND ', $ts);
							if ($sql_where)
							{
								$sql = str_ireplace('ORDER BY', ' AND '.$sql_where.' ORDER BY', $sql);
							}
						}
					}
				}
			}
		}
	}
    
    private function _lnk_of_product_vlist(&$sql, $key, $wd, $map)
    {
        $this->iiSearch($map, $key, $wd);
        switch ($key)
        {
            case 'pid':
                $sql_where = 'p.id'.$wd;
                break;
            case 'sid':
                $sql_where = 'p.sellerid'.$wd;
                break;
            case 'cid':
                $sql_where = 'p.city'.$wd;
                break;
            default:
                $sql_where = '0';
        }
        $sql = str_replace('ORDER BY', ' AND '.$sql_where.' ORDER BY', $sql);
    }
    private function _lnk_of_order_vlist(&$sql, $key, $wd, $map)
    {
        $this->iiSearch($map, $key, $wd);
        switch ($key)
        {
            case 'pid':
                $sql_where = 'o.productid'.$wd;
                break;
            case 'oid':
                $sql_where = 'o.orderid'.$wd;
                break;
            case 'uid':
                $sql_where = 'o.userid'.$wd;
                break;
            default:
                $sql_where = '0';
        }
        $sql = str_replace('ORDER BY', ' AND '.$sql_where.' ORDER BY', $sql);
    }
    private function _lnk_of_coupon_vlist(&$sql, $key, $wd, $map)
    {
        $this->iiSearch($map, $key, $wd);
        switch ($key)
        {
            case 'pid':
                $sql_where = 't.productid'.$wd;
                break;
            case 'oid':
                $sql_where = 't.orderid'.$wd;
                break;
            case 'uid':
                $sql_where = 't.uid'.$wd;
                break;
            case 'coid':
                $sql_where = 't.ticketid'.$wd;
                break;
            default:
                $sql_where = '0';
        }
        $sql = str_replace('ORDER BY', ' AND '.$sql_where.' ORDER BY', $sql);
    }
    private function _lnk_of_delivery_vlist(&$sql, $key, $wd, $map)
    {
        $this->iiSearch($map, $key, $wd);
        switch ($key)
        {
            case 'pid':
                $sql_where = 'o.productid'.$wd;
                break;
            case 'oid':
                $sql_where = 'o.orderid'.$wd;
                break;
            case 'uid':
                $sql_where = 'o.userid'.$wd;
                break;
            default:
                $sql_where = '0';
        }
        $sql = str_replace('ORDER BY', ' AND '.$sql_where.' ORDER BY', $sql);
    }
    private function _lnk_of_recharge_card(&$sql, $key, $wd, $map)
    {
        $this->iiSearch($map, $key, $wd);
        switch ($key)
        {
            case 'rcid':
                $sql_where = 'id'.$wd;
                break;
            default:
                $sql_where = '0';
        }
        $sql = str_replace('ORDER BY', ' AND '.$sql_where.' ORDER BY', $sql);
    }
    private function _lnk_of_recharge_order(&$sql, $key, $wd, $map)
    {
        $this->iiSearch($map, $key, $wd);
        switch ($key)
        {
            case 'orderid':
                $sql_where = 'orderid'.$wd;
                break;
            case 'userid':
                $sql_where = 'userid'.$wd;
                break;
            default:
                $sql_where = '0';
        }
        $sql = str_replace('oRDEr bY', ' AND '.$sql_where.' ORDER BY', $sql);
    }
}

?>