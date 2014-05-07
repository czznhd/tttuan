<?php
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package php
 * @name member.han.php
 * @date 2014-01-23 10:27:10
 */
 


class MemberHandler 
{
	var $ID;	
	var $sid=0;
	
	var $session=array();
	
	var $SessionExists=false;
		
	var $MemberName;
	var $MemberPassword;
	var $MemberFields;
	var $Actions;
	var $CurrentAction;	var $_Error;
	var $_System;
	
	var $CookieHandler=null;
	var $DatabaseHandler=null;
	var $Config=array();
	
	function MemberHandler() 
	{
	    
		$this->DatabaseHandler	=dbc();
		$this->CookieHandler	=handler('cookie');
		$this->Config			=ini('settings');
		
		$this->ID				=0;
		$this->MemberName		='';
		$this->MemberPassword	='';
		$this->ActionList		='';
		$this->CurrentActions	='';
		
		$this->setSessionId();
	}
	function setSessionId($sid=null)
	{
		if($sid!==null)
		{
			$this->sid=$sid;
			$this->CookieHandler->SetVar('sid',$sid,86400*365);
		}
		else
		{
			$cookie_sid=$this->CookieHandler->GetVar('sid');
			$this->sid=(isset($_GET['sid']) || isset($_POST['sid'])) ?
					(isset($_GET['sid']) ? $_GET['sid'] : $_POST['sid']) :
					($cookie_sid ? $cookie_sid : '');
		}
                if(!empty($this->sid)) {
            if(false == preg_match('~^[\w\d]{2,18}$~i', $this->sid)) {
				exit('sid is invalid');
			}
        }
		return $this->sid;
	}
	
	function SetMember($user) 
	{
		if(trim($user)!='') 
		{
			$this->MemberName=$user;
		}
		else 
		{
			Return false;
		}
		
	}
	
	function AuthCode($string, $operation, $key = '') {
			$key = $key? $key : $this->AuthKey;
			if($key=="")Return ;
			$auth_key = md5($key.$_SERVER['HTTP_USER_AGENT']);
			$coded = '';
			$keylength = strlen($key);
			$string = $operation == 'DECODE' ? base64_decode($string) : $string;
			for($i = 0; $i < strlen($string); $i += $keylength) {
					$coded .= substr($string, $i, $keylength) ^ $key;
			}
			$coded = $operation == 'ENCODE' ? str_replace('=', '', base64_encode($coded)) : $coded;
			return $coded;
	}
	
	function SetPassword($pass) 
	{
		if(trim($pass)!='') 
		{
			$this->MemberPassword=$pass;
			$this->MakePasswordHash($password);
		}
		else 
		{
			Return false;
		}
		
	}
	function FetchMember($id, $pass,$secques='')
	{
        $this->ID   = (int) $id;
        $this->MemberPassword = trim($pass);
        $this->Secques=trim($secques);
		$this->MemberFields=$this->GetMember();
		if($this->MemberFields)
		{
			define("MEMBER_ID",(int) $this->MemberFields['uid']);
			define("MEMBER_TRUENAME",$this->MemberFields['truename']);
			define("MEMBER_NAME",$this->MemberFields['username']);
			define("MEMBER_ROLE_ID",(int) $this->MemberFields['role_id']);
			define("MEMBER_ROLE_NAME",$this->MemberFields['role_name']);
			define("MEMBER_ROLE_TYPE",$this->MemberFields['role_type']);
			define('AIJUHE_FOUNDER',(boolean) (MEMBER_ID > 0 && isset($this->Config['aijuhe_founder']) && false!==strpos(",{$this->Config['aijuhe_founder']},",",".MEMBER_ID.",")));
		}
        return true;
	}
	
	function UpdateSessions()
	{
		$onlinehold		=900;		$onlinespan		=$this->Config['onlinespan']=5;		$pvfrequence	=60;		
		
		$session		=array();		
		$session 		=$this->session;
		$timestamp		=time();
		extract($session);
		$uid			=(int)$this->MemberFields['uid'];
		$username		=$this->MemberFields['username'];
		$groupid		=(int)$session['groupid'];
		
				if ($uid && $onlinespan && ($timestamp-($session['lastolupdate']?$session['lastolupdate']:$session['lastactivity']))>$onlinespan*60)
		{
			$session['lastolupdate']=$timestamp;
			$sql="
			UPDATE ".TABLE_PREFIX.'system_onlinetime'."
			SET
				thismonth=thismonth+{$onlinespan},
				total=total+{$onlinespan},
				lastupdate={$timestamp}
			WHERE
				uid=".$uid."
				AND lastupdate<='".($timestamp-$onlinespan*60)."'";
			$this->DatabaseHandler->Query($sql,"UNBUFFERED");
			if (!$this->DatabaseHandler->AffectedRows())
			{
				$sql="
				INSERT INTO ".TABLE_PREFIX.'system_onlinetime'."
					(thismonth,total,lastupdate,uid) 
				values
					({$onlinespan},{$onlinespan},{$timestamp},".$uid.")";
				$this->DatabaseHandler->Query($sql,'SKIP_ERROR');
			}
		}
				
		$session['action']=$this->CurrentAction['id'];
		if ($this->CookieHandler->GetVar('sid')=='' || $this->sid!=$this->CookieHandler->GetVar('sid'))
		{
			$this->setSessionId($this->sid);
		}
		
				if($this->SessionExists==true)
		{
						if($pvfrequence && $uid)
			{
				if($session['spageviews']>=$pvfrequence)
				{
					$sql="
					UPDATE 
						".TABLE_PREFIX.'system_members'." 
					SET 
						pageviews=pageviews+{$session['spageviews']}
					WHERE
						uid=".$uid;
					$this->DatabaseHandler->Query($sql);
					$pageviewsadd = ', pageviews=\'0\'';
				}
				else
				{
					$pageviewsadd = ', pageviews=pageviews+1';
				}
			}
			else
			{
				$pageviewsadd = '';
			}
			$sql="UPDATE ".TABLE_PREFIX.'system_sessions'." SET uid='$uid', username='$username', groupid='$groupid', styleid='{$session['styleid']}', invisible='{$session['invisible']}', action='{$session['action']}', lastactivity='$timestamp', lastolupdate='{$session['lastolupdate']}', seccode='{$session['seccode']}', fid='{$session['fid']}', tid='{$session['tid']}', bloguid='{$session['blogid']}' $pageviewsadd WHERE sid='{$this->sid}'";
			$this->DatabaseHandler->Query($sql);
		}
		else
		{
			$ip=$_SERVER['REMOTE_ADDR'];
			$ips=explode('.',$ip);
			$sql="
			DELETE FROM ".TABLE_PREFIX.'system_sessions'." 
			WHERE 
				sid='{$this->sid}' 
				OR lastactivity<($timestamp-$onlinehold) 
				OR 	('".$uid."'<>'0' AND uid='".$uid."') 
				OR 	(uid='0' AND ip1='$ips[0]' AND ip2='$ips[1]' AND ip3='$ips[2]' AND ip4='$ips[3]' AND lastactivity>$timestamp-60)";
			$this->DatabaseHandler->Query($sql);
			
			$sql="
			INSERT INTO ".TABLE_PREFIX.'system_sessions'." (sid, ip1, ip2, ip3, ip4, uid, username, groupid, styleid, invisible, action, lastactivity, lastolupdate, seccode, fid, tid, bloguid)
			VALUES ('{$this->sid}', '$ips[0]', '$ips[1]', '$ips[2]', '$ips[3]', '$uid', '$username', '$groupid', '{$session['styleid']}', '{$session['invisible']}', '{$session['action']}', '$timestamp', '{$session['lastolupdate']}', '{$session['seccode']}', '{$session['fid']}', '{$session['tid']}', '{$session['bloguid']}')";
			$this->DatabaseHandler->Query($sql,"SKIP_ERROR");
									if($uid && $timestamp - $session['lastactivity'] > 21600) 
			{
				if($oltimespan && $timestamp - $session['lastactivity'] > 86400) 
				{
					$sql="SELECT total FROM ".TABLE_PREFIX.'system_onlinetime'." WHERE uid='$uid'";
					$query = $this->DatabaseHandler->Query($sql);
					$oltime=$query->GetRow();
					$oltimeadd = ', oltime='.round(intval($oltime['total']) / 60);
				} 
				else
				{
					$oltimeadd = '';
				}
				$sql="
				UPDATE 
					".TABLE_PREFIX.'system_members'." 
				SET 
					lastip='$ip', 
					lastvisit=lastactivity, 
					lastactivity='$timestamp'
					$oltimeadd 
				WHERE 
					uid='".$uid."'";
				$this->DatabaseHandler->Query($sql,'mysql_unbuffered_query');
			}
		}
	}

	
	function HasPermission($mod,$act,$is_admin=0) 
	{
				if(MEMBER_ID > 0 && true === AIJUHE_FOUNDER) return true;
		
		$mod=trim($mod);
		$action=trim($act);
		$role_id=$this->MemberFields['role_id'];
		$role_name=$this->MemberFields['role_name'];
		$role_privilege=$this->MemberFields['role_privilege'];
		
		if($role_id==false) 
		{
			$this->_SetError(__("角色编号不能为空,或者该编号在服务器上已经删除"));
			Return false;
		}
		$is_admin=$is_admin?1:0;
		$this->ActionList || $this->ActionList = array();
		if(!$this->ActionList[$mod]) 
		{
			$cache_add=$is_admin?"admin__":"";
			if(($cache_data=cache("role_action/$cache_add{$mod}",-1))===false)
			{
				$sql="
				SELECT
					*
				FROM
					".TABLE_PREFIX.'system_role_action'."
				where
					module='$mod' and is_admin=$is_admin
				ORDER BY
					module,`action`";
				$query = $this->DatabaseHandler->Query($sql);
				$action_list=array();
				while($row=$query->GetRow()) 
				{
					$action_id=$row['id'];
					unset($row['id']);
					unset($row['module']);
					unset($row['is_admin']);
		      		if($row['describe']=='')unset($row['describe']);
		      		if($row['message']=='')unset($row['message']);
		      		if($row['allow_all']==0)unset($row['allow_all']);
		      		if($row['credit_require']=='')unset($row['credit_require']);
		      		if($row['credit_update']=='')unset($row['credit_update']);
		      		if($row['log']==0)unset($row['log']);
					if(strpos($row['action'],'|')!==false) 
					{
						$act_list=explode('|',$row['action']);
						foreach($act_list as $_action) 
						{
							$action_list[(string)$_action]=$action_id;
						}
					}
					else 
					{
						$action_list[(string)$row['action']]=$action_id;
					}
					unset($row['action']);
					$ActionList[$action_id]=$row;
				}
				cache(array($action_list,$ActionList));
			}
			else
			{
				list($action_list,$ActionList)=$cache_data;
			}
			$this->ActionList[$mod]=array('index'=>$action_list,'info'=>$ActionList);
		}
		if((($current_action_id=$this->ActionList[$mod]['index'][$action])!==null) || (($current_action_id=$this->ActionList[$mod]['index']["*"])!==null))
		{
			$current_action=$this->ActionList[$mod]['info'][$current_action_id];
			$this->_SetCurrentAction($current_action);
						if($current_action['credit_require']!='' and MEMBER_ID!=0) 
			{
												
				if(($error_list=$this->_compare_num($current_action['credit_require'],$this->MemberFields))!=0)
				{
					$_error_count=0;
					foreach($error_list as $key=>$error) 
					{
						$credit_name=$this->Config[$error['var_name']];
						if(!empty($credit_name))
						{
							$_error_count++;
							$operator=$error['operator'];
							$this->_SetError("您当前{$credit_name}为({$error['you_num']}),{$current_action['name']}要求{$credit_name}<B>{$operator}</B>{$error['require_num']}。");
						}
					}
					if($_error_count>0)
					{
						$this->_SetError("相更多的了解积分信息，请<A HREF='index.php?mod=member&code=credit_info'>点这里</A>");
						Return false;
					}
				}
				
			}
						if(
				$current_action['credit_update']!='' and 
				MEMBER_ID!=0 and @substr_count($this->CurrentAction['action'],$this->Active['action'])<2
			) 
			{
								if($this->MemberFields['role_type']!='admin') 
				{
					preg_match("~credits([+-][\d]+)~",$current_action['credit_update'],$match);
					$new_credits=$this->MemberFields['credits']+$match[1];
					if($this->MemberFields['role_creditshigher']<=$new_credits and     
						$new_credits<=$this->MemberFields['role_creditslower']
						
					)
					{
											}
					else
					{
						$sql="
						SELECT 
							creditslower-{$new_credits} `offset`,
							id,
							name,
							creditshigher,
							creditslower 
						FROM 
							".TABLE_PREFIX.'system_role'." 
						WHERE 
							creditshigher<={$new_credits} and
							type='{$this->MemberFields['role_type']}'
						ORDER BY `offset` desc
						LIMIT 1";
						$query = $this->DatabaseHandler->Query($sql);
		
						$new_role=$query->GetRow();
						$update_role=",role_id={$new_role['id']}";
					}
				}
								$sql="
				UPDATE
					".TABLE_PREFIX.'system_members'."
				SET 
					{$current_action['credit_update']}{$update_orole}
				WHERE 
					uid=".MEMBER_ID;
				$this->DatabaseHandler->Query($sql);
			}
			if($current_action['allow_all']==1)Return true; 
			if($current_action['allow_all']=='-1') 
			{
				$this->_SetError("系统已经禁止<B>{$current_action['name']}</B>的任何操作");
				Return false;				
			}
						if($this->MemberFields['role_privilege']=="*") Return true;
						if(strpos(",".$role_privilege.",",",".$current_action_id.",")===false) 
			{
				if($ActionList[$current_action_id]['message']!="") 
				{
					$message=$ActionList[$current_action_id]['message'];
				}
				else 
				{
					$message="您的角色({$role_name})没有{$current_action['name']}权限";
				}
				$this->_SetError($message);
				Return false;
			}
		}
		else		{
			if(!$this->Config['safe_mode']) return true; 			if(!$is_admin) return true; 			if('POST' != $_SERVER['REQUEST_METHOD']) return true; 			if((int) $this->Config['aijuhe_founder'] < 1) return true; 			
			$error="操作模块:{$mod}<br>操作指令:{$act}<br><br>";
			$error.=__("由于此操作在系统中没有权限控制,您暂时无法执行该操作,请联系网站的超级管理员。");
			$this->_SetError($error);
			Return false;
		}
		Return true;
	}
	function SetLogItemId($id) 
	{
		$this->Active['item_id']=(int)$id;
	}
	function SetLogItemTitle($title) 
	{
		$this->Active['item_title']=$title;
	}
		function SetLogCredits($field,$credit=0)
	{
		$this->Active[$field]=$credit;
	}
	
	function SetLogURI($uri)
	{
		$this->Active['uri'] = $uri;
	}
	function SetLogUserId($user_id)
	{
		$this->Active['uid'] = $user_id;
	}
	function SetLogUsername($username)
	{
		$this->Active['username'] = $username;
	}
		function SaveActionToLog($title,$auto = true) 
	{
		if($this->CurrentAction['log']==false and true===$auto) 
		{
			Return ;
		}
		$this->SetLogItemTitle($title);
				if($this->Active['item_id']==0 and 
			@substr_count($this->CurrentAction['action'],$this->Active['action'])>1) 
		{
			Return '';
		}
		if($this->IsLogged!=true) 
		{
			if($this->Active['item_title']==false) 
			{
				$this->Active['item_title']=$this->Title;
			}
			$this->Active['action_id']=$this->CurrentAction['id'];
			unset($this->Active['id']);
			$this->DatabaseHandler->SetTable(TABLE_PREFIX. 'system_log');
			if($this->Active['item_id']!=0 and true===$auto)  
			{
				$this->DatabaseHandler->Replace($this->Active);
			}
			else 
			{
				$this->DatabaseHandler->Insert($this->Active);
			}
		}
		$this->IsLogged=true;
	}
	function _SetCurrentAction($action) 
	{
		$this->CurrentAction=$action;
	}
	function GetCurrentAction() 
	{
		Return $this->CurrentAction;
	}
	function GetMemberFields()
	{
		return $this->MemberFields;
	}
	
	function CheckMember($user,$password,$clsF=true) 
	{
		$this->SetMember($user);

		if(check_email($user))
		{
			$type = 'email';
			$password_hash=$this->MakePasswordHash($password.'@4!@#$%@');
		}
		else
		{
			$type = 'username';
			$password_hash=$this->MakePasswordHash($password);
		}

		if(trim($user)!='') 
		{
			$sql="
			Select 
				* 
			FROM 
				".TABLE_PREFIX.'system_members'."
			WHERE 
				{$type}='{$this->MemberName}'";
			$query = $this->DatabaseHandler->Query($sql);
			$this->MemberFields=$query->GetRow();

			if($this->MemberFields['email2']=='zuitu'){
				$password_hash=$this->MakePasswordHash($password.'@4!@#$%@');
			}else{
				$password_hash=$this->MakePasswordHash($password);
			}
			
			if($this->MemberFields!=false) 
			{
				if($this->MemberFields['password']==$password_hash) 
				{
					Return 1;
				}
				else 
				{
					$clsF && $this->MemberFields=array();
					Return -1;
				}
			}
			else 
			{
				Return 0;
			}
		}
	}
	function MakePasswordHash($password) 
	{
			Return md5($password);
	}
	
	function UpdateField($name, $value)
	{
		$sql="
		UPDATE
			".TABLE_PREFIX.'system_members'."
		SET
			{$name}={$value}
		WHERE
			uid=".MEMBER_ID;
		$this->DatabaseHandler->Query($sql);
		return true;
	}
    function GetMember()
    {
        
        $membertablefields = 'M.*';
        if($this->sid)
        {
        	if($this->ID)
        	{
				$sql="
		        SELECT 
					$membertablefields,
					S.sid,
					S.styleid,
					S.lastactivity,
					S.lastolupdate,
					S.pageviews as spageviews,
					S.seccode
		        FROM 
					".TABLE_PREFIX.'system_members'." `M` 
					LEFT JOIN ".TABLE_PREFIX.'system_sessions'." S ON(M.uid=S.uid)
		        WHERE
					M.uid       = {$this->ID} AND
					M.password = '".$this->MemberPassword."' AND
					M.secques	='{$this->Secques}' AND
					S.sid='{$this->sid}' AND 
					CONCAT_WS('.',S.ip1,S.ip2,S.ip3,S.ip4)='{$_SERVER['REMOTE_ADDR']}'
				";
        	}
        	else
        	{
				$sql="
				SELECT 
					sid, groupid, pageviews as spageviews,uid AS sessionuid, lastolupdate,lastactivity,seccode
				FROM 
					".TABLE_PREFIX.'system_sessions'." 
				WHERE 
					sid='{$this->sid}' AND CONCAT_WS('.',ip1,ip2,ip3,ip4)='{$_SERVER['REMOTE_ADDR']}'";
        	}
        	
	        $query = $this->DatabaseHandler->query($sql);
	        	        if (($this->session=$query->GetRow())!=false)
	        {
	        	
		        $this->SessionExists=true;
		        
	        }
	        else
	        {
				$sql="
				SELECT 
					sid, groupid, pageviews, lastolupdate,lastactivity,seccode
				FROM 
					".TABLE_PREFIX.'system_sessions'." 
				WHERE 
					sid='{$this->sid}' AND CONCAT_WS('.',ip1,ip2,ip3,ip4)='{$_SERVER['REMOTE_ADDR']}'";
				$query = $this->DatabaseHandler->Query($sql);
				$this->session=$query->getRow();
				if($this->session)
				{
					$this->SessionExists=true;
					$this->CookieHandler->DeleteVar('sid','auth');
				}
	        }
        }

		if($this->SessionExists==false)
		{
			if($this->ID)
			{
				$sql="
		        SELECT 
					$membertablefields
		        FROM 
					".TABLE_PREFIX.'system_members'." `M` 
		        WHERE
					M.uid       = {$this->ID} AND
					M.password = '".$this->MemberPassword."' AND
					M.secques	='{$this->Secques}'";
		        $query = $this->DatabaseHandler->query($sql);
		        if (($this->session=$query->getRow())==false)
		        {
		        	$this->CookieHandler->DeleteVar('sid','auth');
		        }
			}
	        $this->sid=$this->session['sid']=random(6);
	        $this->session['seccode']=random(6,1);
		}
		
		
		if(empty($this->session['role_id']))
		{
			$this->session=array_merge($this->session,$this->_getGuestRole());
		}
		else
		{
			$cache_name="role/role_".$this->session['role_id'];
			if(($role=cache($cache_name,-1))===false)
			{
				$sql="
				SELECT 	
					id role_id,
					name role_name,
					type role_type,
					creditshigher role_creditshigher,
					creditslower role_creditslower,
					type role_type,
					privilege role_privilege
				FROM
					".TABLE_PREFIX.'system_role'."
				WHERE id={$this->session['role_id']}";
				$query = $this->DatabaseHandler->Query($sql);
				$role=$query->getRow();
				if($role)cache($role);
			}
			$this->session=array_merge($this->session,$role);
		}
        return ($this->Sessions = $this->session);
    }

	function _getGuestRole()
    {

    	if(($fields=cache('role/role_1',-1))===false)
    	{
			$sql="
			SELECT
				R.id role_id,
				R.name role_name,
				R.type role_type,
				R.privilege role_privilege
	        FROM 
				".TABLE_PREFIX.'system_role'." R
	        WHERE 
	            R.id = 1";
			$query = $this->DatabaseHandler->Query($sql);
			
			$fields=$query->GetRow();
			$fields['uid']=0;
			$fields['username']=__("游客");
			cache($fields);
    	}
        return $fields;
    }
	function _SetError($error) 
	{
		$this->_Error[]=$error;
	}
	function GetError() 
	{
		Return $this->_Error;
	}
	
	function _compare_num($condition_str,$array_num)
	{
		extract($array_num);
	
		$compare_str=preg_replace("~([a-z0-9]+)([><])~","\$\\1\\2",$condition_str);
		preg_match_all("~([a-z0-9]+)([><=]{1,2})([0-9]+)~",$condition_str,$compare_list,PREG_SET_ORDER);
		$compare="
			if($compare_str)
			{
				\$error=0;
			}
			else 
			{
				foreach(\$compare_list as \$key=>\$val) 
				{
										if(version_compare(\$\$val[1],\$val[3],\$val[2])==false)
					{
						\$error[]=array(
									'var_name'=>\$val[1],
									'operator'=>\$val[2],
									'require_num'=>\$val[3],
									'you_num'=>\$\$val[1]);
	
					};
	
				}
			}
			";
		eval($compare);
		Return $error;
	}
}
?>