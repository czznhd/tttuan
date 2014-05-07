<?php

/**
* 快递公司运单模板同步功能
* @author Moyo <dev@uuland.org>
* @version 1.0
* @time 2011-10-31 18:28:00
*/

class Express_Corp_Delivery_Sync
{
	private $localStorage = null;
	private $write2file = '@#$%^&*()';
	
	private function STFile()
	{
		return DATA_PATH.'express.cdp.sync.cache.php';
	}
	
	private function store($key, $val = null)
	{
		if ($key === $this->write2file)
		{
			$data = $this->localStorage;
			$data['__AW_upTime__'] = date('Y-m-d H:i:s');
			$write = '<?php  return "'.$this->storeEncrypt($data, 'ENCODE').'"; ?>';
			file_put_contents($this->STFile(), $write);
			return true;
		}
		if (is_null($this->localStorage))
		{
			if (is_file($this->STFile()))
			{
				$encDATA = include $this->STFile();
				$localData = $this->storeEncrypt($encDATA, 'DECODE');
			}
			$this->localStorage = $localData ? $localData : array();
		}
		if (is_null($val))
		{
			return isset($this->localStorage[$key]) ? $this->localStorage[$key] : false;
		}
		$this->localStorage[$key] = $val;
	}
	
	private function storeEncrypt($tar, $ops = 'ENCODE')
	{
		$aclDATA = logic('acl')->Account();
		$skey = md5($aclDATA['account'].'~'.$aclDATA['token']);
		if ($ops == 'ENCODE')
		{
			return authcode(base64_encode(serialize($tar)), 'ENCODE', $skey);
		}
		else
		{
			return unserialize(base64_decode(authcode($tar, 'DECODE', $skey)));
		}
	}
	
	public function localData($key)
	{
		return $this->store('pub_'.$key);
	}
	
	public function time2Check()
	{
		if ($this->noAlert(true))
		{
			return false;
		}
		if (!is_file($this->STFile()))
		{
			return true;
		}
		$cache = fcache('logic.express.cdp.sync', dfTimer('express.cdp.sync'));
		if (!$cache)
		{
			return true;
		}
		return false;
	}
	
	public function noAlert($doCheck = false)
	{
		$lockFile = DATA_PATH.'express.cdp.sync.lock';
		if ($doCheck)
		{
			if (!is_file($lockFile))
			{
				return false;
			}
			$lastWrite = (int)file_get_contents($lockFile);
			if (time() - $lastWrite < 86400*7)
			{
				return true;
			}
			return false;
		}
		file_put_contents($lockFile, time());
	}
	
	public function import($id)
	{
		$data = $this->store('data');
		$cfg = $data[$id];
		if (!$cfg['config'] || !$cfg['background']) return false;
				logic('express')->cdp()->Update($id, array('config' => $cfg['config']));
				$image = dfopen($cfg['background'], 10485760, '', '', true, 10, 'CENWOR.TTTG.CDP.SYNC.AGENT.'.SYS_VERSION.'.'.SYS_BUILD);
		if (!$image) return false;
		$save = handler('io')->initPath(UPLOAD_PATH.'express_cdp/'.$id.'.jpg');
		file_put_contents($save, $image);
				$upload = logic('upload')->AddLocal($save);
				logic('express')->cdp()->Update($id, array('bgid' => $upload['id']));
				return true;
	}
	
	public function checks()
	{
		$lastBuildServer = $this->queryServer('check');
		$lastBuildLocal = $this->store('lastBuild');
		if ($lastBuildServer == $lastBuildLocal)
		{
			fcache('logic.express.cdp.sync', 'lastCheck @ '.date('Y-m-d H:i:s', time()));
			return false;
		}
		if (strlen($lastBuildServer) != 32)
		{
			return false;
		}
		return $lastBuildServer;
	}
	
	public function download()
	{
		$data = $this->queryServer('update');
		if ($data == 'denied')
		{
			return array('status' => 'denied');
		}
		preg_match('/\<\!\[CDATA\[(.*?)\]\]\>/is', $data, $mach);
		if ($mach[1])
		{
			$cfgSers = trim($mach[1]);
			$lastConfigServer = ENC_IS_GBK ? unserialize($cfgSers) : $this->mb_unserialize($cfgSers);
		}
		if (!$lastConfigServer) return array('status' => 'failed');
		if ($lastConfigServer['status'] != 'ok') return array('status' => 'failed');
		preg_match('/::([a-z0-9]{32})::/i', $data, $mach);
		$lastBuildServer = $mach[1];
				$this->store('lastBuild', $lastBuildServer);
		$this->store('data', $lastConfigServer['list']);
		foreach ($lastConfigServer['list'] as $i => $T_one)
		{
			$lastConfigServer['list'][$i] = true;
		}
		$this->store('pub_data', $lastConfigServer['list']);
		$this->store($this->write2file);
		return array('status' => 'ok', 'list' => $this->localData('data'));
	}
	
	private function mb_unserialize($serial_str)
	{
		$serial_str= preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str);
		$serial_str= str_replace("\r", "", $serial_str);
		return unserialize($serial_str);
	}
	
	private function queryServer($action = 'check')
	{
				$server = base64_decode('aHR0cDovL3NlcnZlci50dHR1YW5nb3UubmV0L3Byb2Nlc3Nvci9jZHAvdXBkYXRlLnBocA==').'?charset='.ini('settings.charset').'&';
		if ($action == 'update')
		{
						$aclDATA = logic('acl')->Account();
			$server .= 'account='.$aclDATA['account'].'&token='.$aclDATA['token'].'&';
		}
		$r = dfopen($server.'do='.$action.'&stamp='.logic('misc')->rndString(), 10485760, '', '', true, 10, 'CENWOR.TTTG.CDP.SYNC.AGENT.'.SYS_VERSION.'.'.SYS_BUILD);
		return $r;
	}
}

?>