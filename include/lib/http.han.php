<?php
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package php
 * @name http.han.php
 * @date 2013-03-01 13:21:31
 */
 


class HttpHandler
{
	function HttpHandler()
	{
		
	}
	
	public static function &CheckVars(&$array,$reserve=false)
	{
		foreach($array as $key=>$val)
		{
			if($reserve) return ;
			if($key==false) continue;
			if(is_array($val)==false) 
			{
				$array[$key]=HttpHandler::CleanVal($val);
			}
			else 
			{
				$array[$key]=HttpHandler::CheckVars($val);
			}
		}		

		Return $array;
	}
	
	public static function CleanVal(&$val)
	{
				if(MAGIC_QUOTES_GPC==0) $val = addslashes($val);

				Return $val;
	}
	function UnCleanVal(&$val)
	{
		$val=stripslashes($val);

		return $val;
	}
}

#列子:

?>