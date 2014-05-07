<?php
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package php
 * @name tttuangou.mod.php
 * @date 2014-02-24 14:09:23
 */
 



class ModuleObject extends MasterObject{
	var $city;
	function ModuleObject($config){
		$this->MasterObject($config);		Load::logic('product');
		$this->ProductLogic = new ProductLogic();
		Load::logic('pay');
		$this->PayLogic = new PayLogic();
		Load::logic('me');
		$this->MeLogic = new MeLogic();
		Load::logic('order');
		$this->OrderLogic = new OrderLogic();
		$this -> config =$config;
		$this->ID = (int) ($this->Post['id'] ? $this->Post['id'] : $this->Get['id']);
		Load::moduleCode($this);$this->Execute();
	}
	

	function Execute(){
		switch($this->Code){
			case 'varshow':
				$this->Varshow();
				break;
			case 'varedit':
				$this->Varedit();
				break;
			case 'addcity':
				$this->Addcity();
				break;
			case 'doaddcity':
				$this->Doaddcity();
				break;
			case 'doeditcity':
				$this->Doeditcity();
				break;
			case 'deletecity':
				$this->Deletecity();
				break;
			case 'editcity':
				$this->Editcity();
				break;
			case 'city':
				$this->Listcity();
				break;
			case 'deleteseller':
				$this->Deleteseller();
				break;
			case 'addseller':
				$this->Addseller();
				break;
			case 'doaddseller':
				$this->Doaddseller();
				break;
			case 'editseller':
				$this->Editseller();
				break;
			case 'doeditseller':
				$this->Doeditseller();
				break;
			case 'addmap':
				$this->Addmap();
				break;
			case 'mainseller':
				$this->Mainseller();
				break;
			case 'addproduct':
				$this->Addproduct();
				break;
			case 'doaddproduct':
				$this->Doaddproduct();
				break;
			case 'productorder':
				$this->Productorder();
				break;
			case 'editproduct':
				$this->Editproduct();
				break;
			case 'doeditproduct':
				$this->Doeditproduct();
				break;
			case 'deleteproduct':
				$this->Deleteproduct();
				break;
			case 'deleteproduct':
				$this->Editproduct();
				break;
			case 'refundproduct':
				$this->Refundproduct();
				break;
			case 'listproduct':
				$this->Listproduct();
				break;
			case 'deleteorder':
				$this->Deleteorder();
				break;;
			case 'confirmorder':
				$this->Confirmorder();
				break;
			case 'doconfirmorder':
				$this->Doconfirmorder();
				break;
			case 'listorder':
				$this->Listorder();
				break;
			case 'mailcallpay':
				$this->Mailcallpay();
				break;
			case 'setmail':
				$this->Setmail();
				break;
			case 'dosetmail':
				$this->Dosetmail();
				break;
			case 'addmail':
				$this->Addmail();
				break;
			case 'doaddmail':
				$this->Doaddmail();
				break;
			case 'editmail':
				$this->Editmail();
				break;
			case 'doeditmail':
				$this->Doeditmail();
				break;
			case 'deletemail':
				$this->Deletemail();
				break;
			case 'sendmail':
				$this->Sendmail();
				break;
			case 'dosendmail':
				$this->Dosendmail();
				break;
			case 'mail':
				$this->mail();
				break;
			case 'mailcron':
				$this->MailCronManager();
				break;
			case 'mailcrondo':
				$this->MailCronWorks();
				break;
			case 'deleteemail':
				$this->Deleteemail();
				break;
			case 'email':
				$this->Email();
				break;
			case 'emailsend':
				$this->EmailSends();
				break;
			case 'sms':
				$this->SMSList();
				break;
			case 'smsconfig':
				$this->SMSConfig();
				break;
			case 'smsops':
				$this->SMSOps();
				break;
			case 'onlinepay':
				$this->Onlinepay();
				break;
			case 'setpay':
				$this->setpay();
				break;
			case 'dosetpay':
				$this->Dosetpay();
				break;
			case 'mainpay':
				$this->Mainpay();
				break;
			case 'ticket':
				$this->Ticketz();
				break;
			case 'warnofticket':
				$this->Warnofticket();
				break;
			case 'deleteticket':
				$this->Deleteticket();
				break;
			case 'express':
				$this->ExpressMain();
				break;
			case 'expressmanage':
				$this->ExpressManager();
				break;
			case 'replyquestion':
				$this->Replyquestion();
				break;
			case 'doreplyquestion':
				$this->Doreplyquestion();
				break;
			case 'deletequestion':
				$this->Deletequestion();
				break;
			case 'mainquestion':
				$this->Mainquestion();
				break;
			case 'yesfinder':
				$this->Yesfinder();
				break;;
			case 'nofinder':
				$this->Nofinder();
				break;
			case 'deletefinder':
				$this->Deletefinder();
				break;
			case 'mainfinder':
				$this->Mainfinder();
				break;
			case 'usermsg':
				$this->Usermsg();
				break;
			case 'readusermsg':
				$this->Readusermsg();
				break;
			case 'deleteusermsg':
				$this->Deleteusermsg();
				break;
			case 'clear':
				$this->DataClear();
				break;
			case 'kefuconfig':
				$this->KefuConfig();
				break;
			case 'dataapi':
				$this->DataAPI();
				break;
			case 'imagethumb':
				$this->ImageThumbRebuild();
				break;
			case 'indexnav':
				$this->IndexNaviManager();
				break;
			case 'doindexnav':
				$this->doIndexNaviManager();
				break;
			case 'defaultstyle':
				$this->DefaultStyleManager();
				break;
			case 'dodefaultstyle':
				$this->doDefaultStyleManager();
				break;
			case 'sitelogo':
				$this->SiteLogoManager();
				break;
			case 'dositelogo':
				$this->doSiteLogoManager();
				break;
			case 'shareconfig':
				$this->ShareConfig();
				break;
		};
	}
	function Varshow(){
		$action='?mod=tttuangou&code=varedit';
		$product=ConfigHandler::get('product');
				include (CONFIG_PATH.'settings.php');
		$product['default_imgwidth']=$config['thumbwidth'];
		$product['default_imgheight']=$config['thumbheight'];
		include(handler('template')->file("@admin/tttuangou_var"));
	}
	function Varedit(){
		extract($this->Post);
		$set=ConfigHandler::get('product');
		$set['default_successnum']=$default_successnum;
		$set['default_virtualnum']=$default_virtualnum;
		$set['default_oncemax']=$default_oncemax;
		$set['default_oncemin']=$default_oncemin;
		$set['default_payfinder']=$default_payfinder;
		$set['default_emailcheck']=$default_emailcheck;
		$set['default_googlemapkey']=$default_googlemapkey;
		ConfigHandler::set('product',$set);
		$this->Messager("操作成功",'?mod=tttuangou&code=varshow');
	}
	function Listcity(){
		$city_list=logic('misc')->CityList(0, true);
		$settings = ConfigHandler::get('product');
		$default_city_id = $settings['default_city'];
		include(handler('template')->file("@admin/tttuangou_listcity"));
	}
	
	function Addcity(){
		$action="admin.php?mod=tttuangou&code=doaddcity";
		include(handler('template')->file("@admin/tttuangou_addcity"));
	}
	function Doaddcity(){
		if($this->Post['cityname']=='')$this->Messager("操作失败，地区名称不可以为空");
		$ary=array(
				'cityname'=>$this->Post['cityname'],
				'shorthand'=>$this->Post['shorthand'],
				'display'=>$this->Post['display']
		);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_city');
		$result=$this->DatabaseHandler->Insert($ary);
		$this->Messager("操作成功","?mod=tttuangou&code=city");
	}
	function Editcity(){
		$action="admin.php?mod=tttuangou&code=doeditcity";
		$city=logic('misc')->CityList($this->Get['id'], true);
		$city = $city[0];
		$settings = ConfigHandler::get('product');
		$default_city_id = $settings['default_city'];
		include(handler('template')->file("@admin/tttuangou_editcity"));
	}
	function Doeditcity(){
		$display=$this->Post['display']==''?0:1;
		$ary=array(
				'cityname'=>$this->Post['cityname'],
				'shorthand'=>$this->Post['shorthand'],
				'display'=>$display
		);
		$cityid=$this->Post['cityid'];
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_city');
		$result=$this->DatabaseHandler->Update($ary,'cityid='.$cityid);
				if ('1' == $this->Post['default_city'])
		{
			$settings = ConfigHandler::get('product');
			$settings['default_city'] = $this->Post['cityid'];
			ConfigHandler::set('product', $settings);
		}
		$this->Messager("操作成功","?mod=tttuangou&code=city");
	}
	function Deletecity(){
		$id=$this->Get['id'];
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_city');
		$result=$this->DatabaseHandler->Delete('','cityid='.$id);
		$this->Messager($return ? $return : "操作成功","?mod=tttuangou&code=city");
	}
	function Mainseller(){
			$city = logic('misc')->CityList();
			$newcity=array();
			for($i=0;$i<count($city);$i++){
				$newcity[$city[$i]['cityid']]=$city[$i]['cityname'];
			}
			
			$keyword=$this->Post['keyword']==''?$this->Get['keyword']:$this->Post['keyword'];
			$area=$this->Post['city']==''?$this->Get['city']:$this->Post['city'];
			$addsql='';
			if($keyword!='' || ($area !='false' && $area !='')){
				$addsql=' where 1 ';
				if($keyword!='')$addsql.=' and sellername like \'%'.$keyword.'%\' ';
				if($area!='' && $area !='false')$addsql.=' and area = '.$area.' ';
			}
			$page=intval($_REQUEST['page'])==false?1:intval($_REQUEST['page']);
			$sql='SELECT count(*) from '.TABLE_PREFIX.'tttuangou_seller '.$addsql;
			$query = $this->DatabaseHandler->Query($sql); 
			$num=$query->GetRow();
			$num=$num['count(*)'];
			if($num==0 && $addsql!='')$this->Messager("无法找到匹配的商家","?mod=tttuangou&code=mainseller");
			$pagenum=10;			$page_arr = page($num,$pagenum,$query_link,$_config);		
			
			$sql='SELECT * from '.TABLE_PREFIX.'tttuangou_seller '.$addsql.' limit '.($page-1)*$pagenum.','.$pagenum;
			$query = $this->DatabaseHandler->Query($sql);
			$seller=$query->GetAll();
			foreach ($seller as $i => $one)
			{
				$seller[$i]['money'] *= 1;
			}
			include(handler('template')->file('@admin/tttuangou_seller'));
	}		
	function Addseller(){
			extract($this->Post);
			$city = logic('misc')->CityList();
			$action='?mod=tttuangou&code=doaddseller';
			include(handler('template')->file('@admin/tttuangou_selleradd'));
	}
	function Addmap(){
		extract($this->Get);
		extract($this->Post);
				$x='11728000';
		$y='4320000';
		$z=3;
		if($id!=''){
			$xyz=explode(',',$id);
			$x=$xyz[0];
			$y=$xyz[1];
			$z=$xyz[2];
		}
		include(handler('template')->file('@admin/tttuangou_googlemap'));
	}
		
	function Doaddseller(){
		extract($this->Get);
		extract($this->Post);
		if($username == '' || $password == '' || $sellername=='' || $sellerphone == '' || $selleraddress==''){
			$this->Messager("请将参数都填写完整!", -1);
		}
		$rr = logic('seller')->Register($username, $password);
		$rr['error'] && $this->Messager($rr['result'], -1);
		$uid = $rr['result'];
		$sid = logic('seller')->Add($area, $uid, $sellername, $sellerphone, $selleraddress, $sellerurl, $map);
		if (!$sid) $this->Messager('添加商家失败！请重试', -1);
		logic('gps')->product_linker($sid, $map);
		$this->Messager("操作成功",'?mod=tttuangou&code=mainseller');
	}
		
	function Editseller(){
		extract($this->Get);
		extract($this->Post);
		$city = logic('misc')->CityList();
				$sql='select uid,username from '.TABLE_PREFIX.'system_members  where role_id = 6  ';
		$query = $this->DatabaseHandler->Query($sql);
		$user=$query->GetAll();
		$action='?mod=tttuangou&code=doeditseller';
		$sql='select * from '.TABLE_PREFIX.'tttuangou_seller where userid = '.$id;
		$query = $this->DatabaseHandler->Query($sql);
		$seller=$query->GetRow();
		include(handler('template')->file('@admin/tttuangou_selleredit'));
	}
		
	function Doeditseller(){
		extract($this->Post);
		if ($userid == '-1')
		{
						$rr = logic('seller')->Register($username, $password);
			$rr['error'] && $this->Messager($rr['result'], -1);
			$userid = $rr['result'];
		}
		$ary=array(
			'userid'=> $userid,
			'sellername'=>$sellername,
			'sellerphone'=>$sellerphone,
			'selleraddress'=>$selleraddress,
			'sellerurl'=>$sellerurl,
			'area'=>$area,
			'time'=> time()
		);
		if($map!='')$ary['sellermap']=$map;
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_seller');
		$result=$this->DatabaseHandler->Update($ary,'id='.intval($id));
		if (!$result)
		{
			$this->Messager('您选择的登录用户已经绑定到其他商家，请换一个！', -1);
		}
		logic('gps')->product_linker($id, $map);
		$this->Messager("操作成功","?mod=tttuangou&code=mainseller");
	}
		
	function Deleteseller(){
		extract($this->Get);
		$sql='select * from '.TABLE_PREFIX.'tttuangou_product where sellerid = '.intval($id);
		$query = $this->DatabaseHandler->Query($sql);
		$user=$query->GetAll();
		if(!empty($user))$this->Messager("您必须先删除该商家的产品！才能删除该商家",'?mod=tttuangou&code=mainseller');
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_seller');
		$result=$this->DatabaseHandler->Delete('','id='.intval($id));
		$this->Messager("删除成功",'?mod=tttuangou&code=mainseller');
	}

	function Listproduct(){
		$keyword=$this->Post['keyword']==''?$this->Get['keyword']:$this->Post['keyword'];
		$addsql='';
		if($keyword!='')$addsql=' where name LIKE \'%'.$keyword.'%\'';
				$now=time();
		$sql='update '.TABLE_PREFIX.'tttuangou_product set status=0 where overtime <= '.$now.' and `status` = 1 ';
		$query = $this->DatabaseHandler->Query($sql);
				$page=intval($_REQUEST['page'])==false?1:intval($_REQUEST['page']);
		$sql='SELECT count(*) FROM '.TABLE_PREFIX.'tttuangou_product '.$addsql;
		$query = $this->DatabaseHandler->Query($sql); 
		$num=$query->GetRow();
		$num=$num['count(*)'];
		if($num==0 && $addsql!='')$this->Messager("无法找到匹配的产品","?mod=tttuangou&code=listproduct");
		$pagenum=10;		$page_arr = page($num,$pagenum,$query_link,$_config);
		$sql='SELECT name,id,nowprice,display,city,begintime,overtime,status,successnum,totalnum,virtualnum,`order` FROM '.TABLE_PREFIX.'tttuangou_product '.$addsql.' ORDER BY `order` DESC,id DESC Limit '.($page-1)*$pagenum.','.$pagenum;
		$query = $this->DatabaseHandler->Query($sql);
		$product_list=$query->GetAll();
		foreach ($product_list as $i => $product)
		{
						$product_list[$i]['price'] *= 1;
			$product_list[$i]['nowprice'] *= 1;
		}
		include(handler('template')->file("@admin/tttuangou_listproduct"));
	}
	function Addproduct(){
				$city = logic('misc')->CityList();
				$sql='SELECT * FROM '.TABLE_PREFIX.'tttuangou_seller';
		$query = $this->DatabaseHandler->Query($sql); 
		$seller=$query->GetAll();
		if(empty($seller)){$this->Messager("请先添加一个商家，才能添加产品。", "admin.php?mod=tttuangou&code=addseller");}
		$action="admin.php?mod=tttuangou&code=doaddproduct";
		$product=ConfigHandler::get('product');
		$default_successnum=$product['default_successnum'];
		$default_virtualnum=$product['default_virtualnum'];
		$default_oncemax=$product['default_oncemax'];
		$default_oncemin=$product['default_oncemin'];
				$express_list = logic('express')->GetList();
		include(handler('template')->file("@admin/tttuangou_addproduct"));
	}
	function Doaddproduct(){
		extract($this->Get);
		extract($this->Post);
				if(!($name && $price && $city && $begintime && $overtime)){
			$this->Messager("产品名，原价，城市，开始/结束时间不能为空！", -1);
		};
		if ($nowprice == '')
		{
			$this->Messager("现价不能为空！", -1);
		}
		
		if(!(is_numeric($price) && is_numeric($nowprice) && $price>$nowprice) ){
			$this->Messager("价格必须是数字，而且原始价格必须大于团购价格。", -1);
		};
		$begintime = strtotime($begintime);
		$overtime = strtotime($overtime);
		if($begintime>$overtime) $this->Messager("团购开始时间必须小于结束时间哦！", -1);
		$somewrong = '';
				$img = '';
		if (is_array($_FILES['img']['name']))
		{
						$FILES_O = $_FILES;
			$_FILES = array();
			$loopc = count($FILES_O['img']['name']);
			for ($i=0; $i<$loopc; $i++)
			{
								$_FILES['img']['name'] = $FILES_O['img']['name'][$i];
				$_FILES['img']['type'] = $FILES_O['img']['type'][$i];
				$_FILES['img']['tmp_name'] = $FILES_O['img']['tmp_name'][$i];
				$_FILES['img']['error'] = $FILES_O['img']['error'][$i];
				$_FILES['img']['size'] = $FILES_O['img']['size'][$i];
				$url = upload_image(IMAGE_PATH.'product/','img',$this->config['thumbwidth'],$this->config['thumbheight']);
				if (is_string($url))
				{
					$img .= $url.'|';
				}
				else
				{
					$somewrong = $url['error'];
				}
			}
		}
		elseif($_FILES['img']['name']!='')
		{
						$img=upload_image(IMAGE_PATH.'product/','img',$this->config['thumbwidth'],$this->config['thumbheight']);
			if(is_array($img)) $somewrong = $img['error'];
		};
				$wu = $this->Post['weightunit'];
		$weight = $this->Post['weight'];
		$weight *= ($wu == 'g') ? 1 : 1000;
				$ary=array(
				'city'=>$this->Post['city'],
				'name'=>$this->Post['name'],
				'price'=>$this->Post['price'],
				'nowprice'=>$this->Post['nowprice'],
				'img'=>$img,
				'intro'=>$this->Post['intro'],
				'content'=>$this->Post['content'],
				'cue'=>$this->Post['cue'],
				'theysay'=>$this->Post['theysay'],
				'wesay'=>$this->Post['wesay'],
				'sellerid'=>$this->Post['sellerid'],
				'begintime'=>$begintime,
				'overtime'=>$overtime,
				'type'=>$this->Post['type'],
				'perioddate'=>strtotime($this->Post['perioddate']),
				'weight'=>$weight,
				'successnum'=>$this->Post['successnum'],
				'virtualnum'=>$this->Post['virtualnum'],
				'maxnum'=>$this->Post['maxnum'],
				'oncemax'=>$this->Post['oncemax'],
				'oncemin'=>$this->Post['oncemin'],
				'multibuy'=>$this->Post['multibuy'],
				'allinone'=>$this->Post['allinone'],
				'display'=>$this->Post['display'],
				'addtime'=>time(),
				'order'=>$this->Post['order'],
		);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_product');
		$result=$this->DatabaseHandler->Insert($ary);
		$this->ProductLogic->AddSellerProNum($this->Post['sellerid']);
		if ($somewrong == '')
		{
			$somewrong = '已经成功添加！';
		}
		$this->Messager($somewrong,"?mod=tttuangou&code=listproduct");
	}
	function Productorder(){
		Load::logic('order');
		$this->OrderLogic = new OrderLogic();
		$order=$this->OrderLogic->listProductOrderPayed($this->Get['id']);
		include(handler('template')->file("@admin/tttuangou_productorder"));
		exit;
	}
	function Editproduct(){
		$action="admin.php?mod=tttuangou&code=doeditproduct";
				$sql='SELECT * FROM '.TABLE_PREFIX.'tttuangou_seller';
		$query = $this->DatabaseHandler->Query($sql); 
		$seller=$query->GetAll();
				$city = logic('misc')->CityList();
		$id=$this->Get['id'];
		$sql='SELECT * FROM '.TABLE_PREFIX.'tttuangou_product where id = '.$id;	
		$query = $this->DatabaseHandler->Query($sql); 
		$product=$query->GetRow();
				$product['price'] *= 1;
		$product['nowprice'] *= 1;
				if (false == strpos($product['img'], '|'))
		{
			$product['img'] .= '|';
		}
				$product['weightunit'] = 'g';
		if ($product['weight'] > 1000)
		{
			$product['weightunit'] = 'kg';
			$product['weight'] *= 0.001;
		}
		include(handler('template')->file("@admin/tttuangou_editproduct"));
	}
	function Doeditproduct(){
		extract($this->Get);
		extract($this->Post);
				if(!($name && $price && $city && $begintime && $overtime)){
			$this->Messager("产品名，原价，城市，开始/结束时间不能为空！", -1);
		};
		if ($nowprice == '')
		{
			$this->Messager("现价不能为空！", -1);
		}
		
		if(!(is_numeric($price) && is_numeric($nowprice) && $price>$nowprice) ){
			$this->Messager("价格必须是数字，而且原始价格必须大于团购价格。", -1);
		};
		$begintime = strtotime($begintime);
		$overtime = strtotime($overtime);
		if($begintime>$overtime) $this->Messager("团购开始时间必须小于结束时间哦！", -1);
		$somewrong = '';
				if ('' != $img_removes)
		{
						$imgs = explode('|', $img_removes);
			for ($i=0; $i<count($imgs); $i++)
			{
				if ('' != $imgs[$i])
				{
										@unlink(IMAGE_PATH.'product/'.$imgs[$i]);
										@unlink(IMAGE_PATH.'product/s-'.$imgs[$i]);
					$img_database = str_replace($imgs[$i].'|', '', $img_database);
				}
			}
		}
				$img = $img_database;
				$img = str_replace('||', '', $img);
		if (substr($img, 0, 1) == '|') $img = substr($img, 1);
				if (is_array($_FILES['img']['name']))
		{
						$FILES_O = $_FILES;
			$_FILES = array();
			$loopc = count($FILES_O['img']['name']);
			for ($i=0; $i<$loopc; $i++)
			{
								$_FILES['img']['name'] = $FILES_O['img']['name'][$i];
				$_FILES['img']['type'] = $FILES_O['img']['type'][$i];
				$_FILES['img']['tmp_name'] = $FILES_O['img']['tmp_name'][$i];
				$_FILES['img']['error'] = $FILES_O['img']['error'][$i];
				$_FILES['img']['size'] = $FILES_O['img']['size'][$i];
				$url = upload_image(IMAGE_PATH.'product/','img',$this -> config['thumbwidth'],$this -> config['thumbheight']);
				if (is_string($url))
				{
					$img .= $url.'|';
				}
				else
				{
					$somewrong = $url['error'];
				}
			}
		}
		elseif($_FILES['img']['name']!='')
		{
						$img_ups = upload_image(IMAGE_PATH.'product/','img',$this -> config['thumbwidth'],$this -> config['thumbheight']);
			if(is_array($img_ups)) $somewrong = $img_ups['error'];
			$img .= $img_ups;
		};
				$wu = $this->Post['weightunit'];
		$weight = $this->Post['weight'];
		$weight *= ($wu == 'g') ? 1 : 1000;
				$ary=array(
				'city'=>$this->Post['city'],
				'name'=>$this->Post['name'],
				'price'=>$this->Post['price'],
				'sellerid'=>$this->Post['sellerid'],
				'nowprice'=>$this->Post['nowprice'],
				'intro'=>$this->Post['intro'],
				'content'=>$this->Post['content'],
				'cue'=>$this->Post['cue'],
				'theysay'=>$this->Post['theysay'],
				'wesay'=>$this->Post['wesay'],
				'content'=>$this->Post['content'],
				'begintime'=>$begintime,
				'overtime'=>$overtime,
				'type'=>$this->Post['type'],
				'perioddate'=>strtotime($this->Post['perioddate']),
				'weight'=>$weight,
				'successnum'=>$this->Post['successnum'],
				'virtualnum'=>$this->Post['virtualnum'],
				'maxnum'=>$this->Post['maxnum'],
				'oncemax'=>$this->Post['oncemax'],
				'oncemin'=>$this->Post['oncemin'],
				'multibuy'=>$this->Post['multibuy'],
				'allinone'=>$this->Post['allinone'],
				'display'=>$this->Post['display'],
				'img'=>$img,
				'order'=>$this->Post['order'],
		);
				$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_product');
		$result=$this->DatabaseHandler->Update($ary,'id='.$id);
				if ($this->Post['sellerid'] != $this->Post['sellerid_old'])
		{
			$this->ProductLogic->DelSellerProNum($this->Post['sellerid_old']);
			$this->ProductLogic->AddSellerProNum($this->Post['sellerid']);
		}
		if ($somewrong == '')
		{
			$somewrong = '已经成功修改！';
		}
		$this->Messager($somewrong,"?mod=tttuangou&code=listproduct");
	}
	function Deleteproduct(){
		$id=intval($this->Get['id']);
		$sql='select sellerid from '.TABLE_PREFIX.'tttuangou_product where id = '.$id;
		$query = $this->DatabaseHandler->Query($sql); 
		$product=$query->GetRow();
		
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_product');
		$result=$this->DatabaseHandler->Delete('','id='.$id);
		$this->ProductLogic->DelSellerProNum($product['sellerid']);
		$this->Messager("操作成功",'?mod=tttuangou&code=listproduct');
	}
	function Refundproduct(){
		extract($this->Get);
		extract($this->Post);
		$id=intval($id);
				$sql='SELECT status,nowprice,name FROM '.TABLE_PREFIX.'tttuangou_product where id = '.$id;
		$query = $this->DatabaseHandler->Query($sql); 
		$product=$query->GetRow();
		if($product['status']==0){
						$sql='select o.*,m.username,m.email,p.sellerid from '.TABLE_PREFIX.'tttuangou_order o left join '.TABLE_PREFIX.'system_members m on o.userid= m.uid LEFT join '.TABLE_PREFIX.'tttuangou_product p on o.productid=p.id where o.productid = '.$id.' and o.pay = 1 and o.status = 1';
			$query = $this->DatabaseHandler->Query($sql); 
			$order=$query->GetAll();
			if($order==''){
				$this->Messager("没有订单，不需要退款",'?mod=product');
			}else{
				foreach($order as $i => $value){
										$ary=array(
					'address'=> $value['email'],
					'username'=>$value['username'],
					'title'=>'团购失败提示信息',
					'content'=>'非常抱歉！您团购的产品（'.$product['name'].'）因为购买人数不足而失败了！货款已经返还您的账户请点<a href="'.$this->Config['site_url'].'">这里</a>查看详细！',
					'addtime'=>time()
					);
					$keys=$values='';
					foreach($ary as $i => $valuez){
						$a=$i=='addtime'?"":',';
						$keys.='`'.$i.'`'.$a;
						$values.='\''.$valuez.'\''.$a;
					}
					$sql='insert into '.TABLE_PREFIX.'tttuangou_cron ('.$keys.') VALUES ('.$values.')';
					$this->DatabaseHandler->Query($sql);
				
					$price=$value['productnum']*$product['nowprice'];
					$this->ProductLogic->delSellerTotMoney($value['sellerid'],$price);
										$this->MeLogic->moneyAdd($price,$value['userid']);					$ary=array(
						'userid' => $value['userid'],
						'type' => 1,
						'name' => '失败团购返款账户',
						'intro' => '返还您团购金额'.$price.'元',
						'money' => $price,
						'time' => time(),
					);
					$this->MeLogic->moneyLog($ary);
				};
			};
			$this->ProductLogic->productTypedit($id,3);
						$ary=array(
				'status' => 3,
			);
			$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_order');
			$result=$this->DatabaseHandler->Update($ary,'productid='.$id);		
			$this->Messager("返款成功",'?mod=tttuangou&code=listproduct');		
		};
		$this->Messager("错误不能退款",'?mod=tttuangou&code=listproduct');
	}

	function Listorder(){
				$keyword=$this->Post['keyword']==''?$this->Get['keyword']:$this->Post['keyword'];
		$searchtype=$this->Post['type']==''?$this->Get['type']:$this->Post['type'];
		$status=$this->Post['status']==''?$this->Get['status']:$this->Post['status'];
		$addsql='';
		if($keyword!='' || ($status!='false' && $status!='')){
			$addsql=' where 1 ';
			$type=$searchtype==1?'o.orderid':'m.username';
			if($keyword!='')$addsql.= ' AND '.$type.' LIKE \'%'.$keyword.'%\'';
			if($status!='false' && $status!='')$addsql.= ' AND o.pay = '.$status.' ';
		}
		$page=intval($_REQUEST['page'])==false?1:intval($_REQUEST['page']);
		$sql='SELECT count(*) FROM '.TABLE_PREFIX.'tttuangou_order o left join '.TABLE_PREFIX.'tttuangou_product p on o.productid = p.id left join '.TABLE_PREFIX.'system_members m on m.uid=o.userid '.$addsql;
		$query = $this->DatabaseHandler->Query($sql); 
		$num=$query->GetRow();
		if($addsql!='' && $num['count(*)']==0)$this->Messager("无法搜索到匹配的订单",'?mod=tttuangou&code=listorder');
		$num=$num['count(*)'];
		$pagenum=10;		$page_arr = page($num,$pagenum,$query_link,$_config);
		$sql='SELECT o.*,p.name,p.nowprice,p.type,m.username,m.qq,m.phone FROM '.TABLE_PREFIX.'tttuangou_order o left join '.TABLE_PREFIX.'tttuangou_product p on o.productid = p.id left join '.TABLE_PREFIX.'system_members m on m.uid=o.userid '.$addsql.' order by buytime DESC  limit '.($page-1)*$pagenum.','.$pagenum;
		$query = $this->DatabaseHandler->Query($sql); 
		$list=$query->GetAll();
		foreach($list as $i => $value){
			$order[$i]=$value;
			$order[$i]['money']=$value['nowprice']*$value['productnum'];
						$order[$i]['nowprice'] *= 1;
		};
		include(handler('template')->file("@admin/tttuangou_listorder"));
	}
	
	function Mailcallpay(){
		$id=$this->Get['id'];
		$sql='select o.*,m.email,m.username,p.name,p.perioddate from '.TABLE_PREFIX.'tttuangou_order o left join '.TABLE_PREFIX.'system_members m on o.userid=m.uid left join '.TABLE_PREFIX.'tttuangou_product p on o.productid = p.id where o.orderid = '.$id;
		$query = $this->DatabaseHandler->Query($sql);
		$order=$query->GetRow();
		$ary=array(
		'address'=> $order['email'],
		'username'=>$order['username'],
		'title'=>'订单支付温馨提示',
		'content'=>'温馨提示您团购的产品（'.$order['name'].')即将结束,而您还没有付款，想拥有现在是好机会啊，请点<a href="'.$this->Config['site_url'].'">这里完成付款立刻拥有该商品</a>！',
		'addtime'=>time()
		);
		$keys=$values='';
		foreach($ary as $i => $valuez){
			$a=$i=='addtime'?"":',';
			$keys.='`'.$i.'`'.$a;
			$values.='\''.$valuez.'\''.$a;
		}
		$sql='insert into '.TABLE_PREFIX.'tttuangou_cron ('.$keys.') VALUES ('.$values.')';
		$this->DatabaseHandler->Query($sql);
		$this->Messager("邮件通知成功!");
	}
	
	function Confirmorder(){
		$id=$this->Get['id'];
		$sql='SELECT o.*,p.name,p.nowprice FROM '.TABLE_PREFIX.'tttuangou_order o left join '.TABLE_PREFIX.'tttuangou_product p on o.productid = p.id where o.orderid = '.$id ;
		$query = $this->DatabaseHandler->Query($sql); 
		$order=$query->GetRow();
		$action='?mod=tttuangou&code=doconfirmorder';
		include(handler('template')->file("@admin/tttuangou_confirmorder"));
	}
	function Doconfirmorder(){
		extract($this->Post);
		if($type=='' || doubleval($money)=='' || $number=='') $this->Messager("您必须详细记录所有交易信息!");
		if($money <= 0) $this->Messager("错误，交易金额必须是一个大于0的数!");
		$money = doubleval($money);
		$order=logic('order')->GetOne($orderid);
		if($order['pay']==1)$this->Messager("错误，该订单已经支付过了!");
		logic('me')->money()->add($money,$order['userid'], array(
			'name' => '充值(后台)',
			'intro' => '您充值了'.$money.'元，由管理员（'.MEMBER_NAME.'）确认收到货款',
		));
		$product = $this->ProductLogic->productCheck($order['productid']);
		if($product['id'] == '')
		{
			$this->Messager("错误~该产品不存在或已结束团购!");
		};
		if($product['maxnum']!=0){
			$surplusnum=$this->ProductLogic->Surplus($product['maxnum'], $order['productid']);
			if($surplusnum<=0){
				$this->Messager("该产品被人抢先购买了，钱已经充值到个人账户！");
			}
			elseif ($surplusnum < $order['productnum'])
			{
				$this->Messager('本产品仅剩余 '.($surplusnum).' 份，无法满足客户的购买量！');
			}
		};
		$price=$product['nowprice']*$order['productnum']+$order['expressprice'];
		$result=$this->MeLogic->money()->count($order['userid']);
		if($price<=$result){
			$express = '';
			if ($order['expressprice'] > 0)
			{
				$express = sprintf(__('运费：%.2f'), $order['expressprice']);
			}
			$result=$this->MeLogic->money()->pay($price, $order['userid'], array(
				'name' => '团购商品',
				'intro' => sprintf(__('商品名：%s<br/>单价：%.2f<br/>数目：%d<br/>%s'), $product['name'], $order['productprice'], $order['productnum'], $express)
			));
			$this->ProductLogic->AddSellerTotMoney($product['sellerid'],$price);
		}else{
			$this->Messager("余额不足，不够支付本次消费！");
		};
		$result=$this->OrderLogic->orderType($orderid,1,1,1);
		$this->Ticket($orderid, $order['userid']);
		$this->Messager("订单修改成功！","?mod=tttuangou&code=listorder");
	}
	
	function Deleteorder(){
		$id=$this->Get['id'];
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_order');
		$result=$this->DatabaseHandler->Delete('','orderid='.$id);
		$this->Messager($return ? $return : "操作成功","?mod=tttuangou&code=listorder");
	}
	function Warnofticket(){
		extract($this->Get);
		extract($this->Post);
		$id=intval($id);
				$sql='select t.*,m.email,m.username,p.name,p.perioddate from '.TABLE_PREFIX.'tttuangou_ticket t left join '.TABLE_PREFIX.'system_members m on t.uid=m.uid left join '.TABLE_PREFIX.'tttuangou_product p on t.productid = p.id where t.ticketid = '.$id;
		$query = $this->DatabaseHandler->Query($sql);
		$ticket=$query->GetRow();
		
		$ary=array(
		'address'=> $ticket['email'],
		'username'=>$ticket['username'],
		'title'=>'购物券即将到期提示信息',
		'content'=>'温馨提示您购买的产品（'.$ticket['name'].'）团购券即将于'.date('Y-m-d', $ticket['perioddate']).'到期请尽快消费以免过期，请点<a href="'.$this->Config['site_url'].'">这里</a>查看您的团购券！',
		'addtime'=>time()
		);
		$keys=$values='';
		foreach($ary as $i => $valuez){
			$a=$i=='addtime'?"":',';
			$keys.='`'.$i.'`'.$a;
			$values.='\''.$valuez.'\''.$a;
		}
		$sql='insert into '.TABLE_PREFIX.'tttuangou_cron ('.$keys.') VALUES ('.$values.')';
		$this->DatabaseHandler->Query($sql);
		$this->Messager("您已经成功提醒了该用户！",'?mod=tttuangou&code=ticket');
	}
	
	function Ticket($orderid,$userID){				$sql='SELECT p.name,p.type,p.nowprice,o.productid,p.id,p.city,p.successnum,o.orderid,o.userid as ouid,o.productnum FROM '.TABLE_PREFIX.'tttuangou_order o left join '.TABLE_PREFIX.'tttuangou_product p on o.productid = p.id where  o.orderid = '.$orderid;
		$query = $this->DatabaseHandler->Query($sql);
		$order=$query->GetRow();
		if($order=='') $this->Messager("错误，无法找到类似订单！");
				
		$product=$this->ProductLogic->GetOne($order['id']);
		$num=$product['num'];
		if($num<$order['successnum']){
			$this->Messager('您已经成功团购该产品，但团购差'.($order['successnum']-$num).'人达成 ，快去邀请好友参加团购，还能获得丰厚获取奖励!','?mod=tttuangou&code=listorder');
		}
		if($num==$order['successnum'])
		{
			$this->ProductLogic->productTypedit($order['productid'],2);
			$sql='select o.*,m.username,m.email from '.TABLE_PREFIX.'tttuangou_order o left join '.TABLE_PREFIX.'system_members m on o.userid= m.uid where o.productid = '.$order['id'].' and o.pay = 1 and o.status = 1';
			$query = $this->DatabaseHandler->Query($sql);
			$orderPayed=$query->GetAll();
			foreach($orderPayed as $i => $value){
				$usreid=intval($value['userid']);
				$ary=array(
				'address'=> $value['email'],
				'username'=>$value['username'],
				'title'=>'团购成功提示信息',
				'content'=>'恭喜您在本站团购的产品('.$order['name'].')在'.date('Y-m-d H:i',time()).'团购成功，请点<a href="'.$this->Config['site_url'].'">这里</a>查看您的团购券！',
				'addtime'=>time()
				);
				$this->MeLogic->mailCron($ary);
				$this->MeLogic->finder($usreid,$order['id']);
				for($i=1;$i<=$value['productnum'];$i++){
					$result=$this->MeLogic->ticketCreate($usreid,$order['id'],$value['orderid']);
					if($result=='')$i--;
				};
			};
			$this->Messager("团购成功，您还可以推荐您的朋友来购买哦！",'?mod=tttuangou&code=listorder');
		};
		$this->ProductLogic->productTypedit($order['productid'],2);
		$member = $this->MeLogic->infoMe($userID);
		$ary=array(
		'address'=> $member['email'],
		'username'=>$member['username'],
		'title'=>'团购成功提示信息',
		'content'=>'恭喜您在本站团购的产品('.$order['name'].')在'.date('Y-m-d H:i',time()).'团购成功，请点<a href="'.$this->Config['site_url'].'">这里</a>查看您的团购券！',
		'addtime'=>time()
		);
		$this->MeLogic->mailCron($ary);
		$this->MeLogic->finder($userID,intval($order['id']));
		for($i=1;$i<=$order['productnum'];$i++){
			$result=$this->MeLogic->ticketCreate($order['ouid'],$order['productid'],$order['orderid']);
			if($result=='')$i--;
		};
		$this->Messager("团购成功，您还可以推荐您的朋友来购买哦！",'?mod=tttuangou&code=listorder');
	}

	function Mail(){
		$sql='select mid,name,intro,title from '.TABLE_PREFIX.'tttuangou_mail ';
		$query = $this->DatabaseHandler->Query($sql);
		$mail=$query->GetAll();
		include(handler('template')->file('@admin/tttuangou_mail'));
	}
	
	function Setmail(){
		$set=ConfigHandler::get('product');
		$action='?mod=tttuangou&code=dosetmail';
		if ('' == $set['default_mail_from'])
		{
			$set['default_mail_from'] = $this->Config['site_admin_email'];
		}
		if ('' == $set['default_mail_user'])
		{
			$set['default_mail_user'] = $this->Config['site_name'];
		}
		if ('' == $set['default_mail_maxonce'])
		{
			$set['default_mail_maxonce'] = 3;
		}
		include(handler('template')->file('@admin/tttuangou_mailset'));
	}

	function Dosetmail(){
		extract($this->Post);
		$set=ConfigHandler::get('product');
		$set['default_mailtype']=$default_mailtype;
		$set['default_server']=$server;
		$set['default_port']=$port;
		$set['default_user']=$user;
		$set['default_pwd']=$password;
		$set['default_mail_from'] = $mail_from;
		$set['default_mail_user'] = $mail_user;
		$set['default_mail_maxonce'] = $mail_maxonce;
		ConfigHandler::set('product',$set);
		$this->Messager("操作成功",'?mod=tttuangou&code=mail');
	}

	function Addmail(){
		$action='?mod=tttuangou&code=doaddmail';
		include(handler('template')->file('@admin/tttuangou_addmail'));
	}
	
	function Doaddmail(){
		extract($this->Post);
		$ary=array(
			'name' => $name,
			'intro' => $intro,
			'title' => $title,
			'content' => $content,
		);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_mail');
		$result=$this->DatabaseHandler->Insert($ary);
		$this->Messager("操作成功",'?mod=tttuangou&code=mail');
	}
	function Editmail(){
		extract($this->Get);
		$action='?mod=tttuangou&code=doeditmail';
		$sql='select * from '.TABLE_PREFIX.'tttuangou_mail where mid = '.intval($id);
		$query = $this->DatabaseHandler->Query($sql);
		$mail=$query->GetRow();
		include(handler('template')->file('@admin/tttuangou_editmail'));
	}

	function Doeditmail(){
		extract($this->Post);
		$ary=array(
			'name' => $name,
			'intro' => $intro,
			'title' => $title,
			'content' => $content,
		);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_mail');
		$result=$this->DatabaseHandler->Update($ary,'mid='.$mid);
		$this->Messager("操作成功","?mod=tttuangou&code=mail");
	}

	function Deletemail(){
		extract($this->Get);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_mail');
		$result=$this->DatabaseHandler->Delete('','mid='.$id);
		$this->Messager("操作成功",'?mod=tttuangou&code=mail');
	}

	function Sendmail(){
		extract($this->Get);
		extract($this->Post);
		$action='?mod=tttuangou&code=dosendmail';
		$sql='select * from '.TABLE_PREFIX.'tttuangou_mail where mid = '.$id;
		$query = $this->DatabaseHandler->Query($sql);
		$mail=$query->GetRow();
		$sql='SELECT * FROM '.TABLE_PREFIX.'tttuangou_city';
		$query = $this->DatabaseHandler->Query($sql); 
		$city_list=$query->GetAll();
		include(handler('template')->file('@admin/tttuangou_sendmail'));
	}

	function Dosendmail(){
		extract($this->Get);
		extract($this->Post);
				$sql='select * from '.TABLE_PREFIX.'tttuangou_email';
		if(intval($city)!=0){
			$sql .= ' where city = '.$city;
		};
		$query = $this->DatabaseHandler->Query($sql);
		$email=$query->GetAll();
		
		$sql='select * from '.TABLE_PREFIX.'tttuangou_mail where mid = '.$mid;
		$query = $this->DatabaseHandler->Query($sql);
		$mail=$query->GetRow();
		
		foreach($email as $value){
				$ary=array(
					'address'=> $value['email'],
					'username'=>'尊敬的用户',
					'title'=>$mail['title'],
					'content'=>$mail['content'],
					'addtime'=>time()
					);
					$keys=$values='';
					foreach($ary as $i => $valuez){
						$a=$i=='addtime'?"":',';
						$keys.='`'.$i.'`'.$a;
						$values.='\''.$valuez.'\''.$a;
					}
					$sql='insert into '.TABLE_PREFIX.'tttuangou_cron ('.$keys.') VALUES ('.$values.')';
					$this->DatabaseHandler->Query($sql);
		}
		
		$this->Messager("邮件已写入计划任务表，将适时发送！",'?mod=tttuangou&code=mail');
	}

	function Email(){
		extract($this->Get);
		extract($this->Post);
				$city = logic('misc')->CityList();
		foreach($city as $i => $value){
			$mycity[$value['cityid']]=$value['cityname'];
		};

		$page=intval($_REQUEST['page'])==false?1:intval($_REQUEST['page']);
		$sql='SELECT count(*) FROM '.TABLE_PREFIX.'tttuangou_email';
		$query = $this->DatabaseHandler->Query($sql); 
		$num=$query->GetRow();
		$num=$num['count(*)'];
		$pagenum=15;		$page_arr = page($num,$pagenum,$query_link,$_config);

				$sql='select * from '.TABLE_PREFIX.'tttuangou_email limit '.($page-1)*$pagenum.','.$pagenum;
		$query = $this->DatabaseHandler->Query($sql);
		$mail=$query->GetAll();
		include(handler('template')->file('@admin/tttuangou_email'));
	}	
	function Deleteemail(){
		extract($this->Get);
		extract($this->Post);
		$id=intval($id);
		$sql='delete from '.TABLE_PREFIX.'tttuangou_email where id = '.$id;
		$query = $this->DatabaseHandler->Query($sql);
		$this->Messager("删除成功",'?mod=tttuangou&code=email');
	}

		function SMSList()
	{
		$filter = $_GET['filter'];
		if ($filter == '')
		{
			$filter = 'all';
		}
		if ($filter == 'all')
		{
			$filter_type = 'fails';
			$filter_text = '查看发送失败的信息';
		}
		else
		{
			$filter_type = 'all';
			$filter_text = '查看所有发送的信息';
		}
		$sql_cond = ' WHERE 1 ';
		if ('fails' == $filter)
		{
			$sql_cond = ' WHERE state<>"短信发送成功！" ';
		}
		$op = $_GET['op'];
		$sql_search = '';
		$link = '';
		if ('search' == $op)
		{
			$byway = isset($_GET['byway']) ? $_GET['byway'] : $_POST['byway'];
			$bywhat = isset($_GET['bywhat']) ? $_GET['bywhat'] : $_POST['bywhat'];
			$sql_search = ' AND '.$byway.'="'.$bywhat.'"';
			$link = '&op=search&byway='.$byway.'&bywhat='.$bywhat;
		}
		$page=intval($_GET['page'])==false?1:intval($_GET['page']);
		$pagenum = 20; 					$c_sql = 'SELECT COUNT(*) FROM '.TABLE_PREFIX.'tttuangou_sms'.$sql_cond.$sql_search;
			$c_result = $this->DatabaseHandler->Query($c_sql);
			if ($c_result)
			{
				$c_result_row = $c_result->GetRow();
				$num = $c_result_row['COUNT(*)'];
			}
			else
			{
				$num = 1;
			}
		$page_arr = page($num, $pagenum, 'admin.php?mod=tttuangou&code=sms&filter='.$filter.$link);
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'tttuangou_sms'.$sql_cond.$sql_search.'ORDER BY id DESC LIMIT '.($page-1)*$pagenum.','.$pagenum;
		$query = $this->DatabaseHandler->Query($sql);
		if ($query)
		{
			$sms = $query->GetAll();
		}
		include(handler('template')->file('@admin/tttuangou_sms'));
	}
	function SMSOps()
	{
		extract($this->Get);
		switch ($op)
		{
			case 'update':
				if ($phone != '')
				{
					$sql = 'UPDATE '.TABLE_PREFIX.'tttuangou_sms SET phone="'.$phone.'" WHERE id='.$id;
				}
				elseif ($content != '')
				{
					$content = iconv('UTF-8', 'GB2312/'.'/IGNORE', $content);
					$sql = 'UPDATE '.TABLE_PREFIX.'tttuangou_sms SET content="'.$content.'" WHERE id='.$id;
				}
				$this->DatabaseHandler->Query($sql);
				echo 'ok';
				break;
			case 'delete':
				$sql = 'DELETE FROM '.TABLE_PREFIX.'tttuangou_sms WHERE id='.$id;
				$this->DatabaseHandler->Query($sql);
				$this->Messager('删除操作成功！');
				break;
			case 'resend':
				$sql = 'SELECT * FROM '.TABLE_PREFIX.'tttuangou_sms WHERE id='.$id;
				$sms = $this->DatabaseHandler->Query($sql)->GetRow();
								Load::functions('sms');
				$result = sms_send($sms['phone'], $sms['content'], false);
				$msgid = $result['msgid'];
				$msgstate = $result['msgstate'];
								$sql = 'UPDATE '.TABLE_PREFIX.'tttuangou_sms SET mid_resend="'.$msgid.'",state="'.$msgstate.'" WHERE id='.$id;
				$this->DatabaseHandler->Query($sql);
								$this->Messager($msgstate);
				break;
			case 'dels':
								$time_calc = intval($_POST['timec']);
				$time_unit = $_POST['timeu'];
				$time_rule = array('s'=>1,'m'=>60,'h'=>3600, 'd'=>86400);
				$time_delete_in = time() - ( $time_calc * $time_rule[$time_unit] );
				$sql = 'DELETE FROM '.TABLE_PREFIX.'tttuangou_sms WHERE `mid` <= "SMS'.$time_delete_in.'"';
				$this->DatabaseHandler->Query($sql);
				$affect = $this->DatabaseHandler->AffectedRows();
				$this->Messager('删除操作成功！共有 '.$affect.' 条记录被删除');
				break;
		}
	}
	function SMSConfig()
	{
		extract($this->Get);
		if (isset($op) && $op == 'submit')
		{
			extract($this->Post);
			$sms['power'] = trim($power);
			$sms['server'] = trim($server);
			$sms['account'] = trim($account);
			$sms['password'] = trim($password);
			$sms['template'] = $template;
			ConfigHandler::set('sms', $sms);
			$this->Messager('保存完成！');
		}
		elseif (isset($op) && $op == 'smsprint')
		{
									$sql = '
					SELECT
						p.id, p.name, p.perioddate, p.type, s.sellerphone, s.selleraddress
					FROM
						'.TABLE_PREFIX.'tttuangou_product p LEFT join '.TABLE_PREFIX.'tttuangou_seller s ON (p.sellerid=s.id) WHERE p.type != "stuff" AND p.overtime > '.time().' ORDER BY p.id DESC
					LIMIT 0,1';
			$ticketInfo = $this->DatabaseHandler->Query($sql)->GetRow();
			$response = str_replace(
					array(
						'{user_name}', '{product_name}', '{ticket_number}', '{ticket_password}', '{perioddate}', '{seller_phone}', '{seller_address}', '{site_name}'
					),
					array(
						MEMBER_NAME, $ticketInfo['name'], '123456789012', '123456', date('Y-m-d', $ticketInfo['perioddate']), $ticketInfo['sellerphone'], $ticketInfo['selleraddress'], $this->Config['site_name']
					),
			$template);
			echo '<meta http-equiv="Content-Type" content="text/html; charset='.$this->Config['charset'].'" />';
			echo '<div style="font-size:10pt;margin:0;padding:0;">';
			echo '<textarea id="sms_content" style="width:100%;height:100px;">'.$response.'</textarea>';
			$strlength = mb_strlen($response, 'UTF-8');
			echo '<br/><br/>总字数：'.$strlength.'，估计需要折合'.ceil($strlength/70).'条短信发送。';
			echo '<br/><br/><a href="javascript:window.location=\'?mod=tttuangou&code=smsconfig&op=smssend&content=\'+encodeURIComponent(document.getElementById(\'sms_content\').value)+\'\';">发送到手机预览</a> [ <a href="index.php?mod=me&code=info" target="_blank">填写手机号码</a> ]';
			echo '</div>';
			return;
		}
		elseif (isset($op) && $op == 'smssend')
		{
			Load::functions('sms');
			$result = sms_send($this->MemberHandler->MemberFields['phone'], iconv('UTF-8', 'GB2312/'.'/IGNORE', $content));
			echo $result['msgstate'];
			return;
		}
		elseif (isset($op) && $op == 'smsaccount')
		{
			Load::functions('sms');
			$time_start = microtime_float();
			$result = sms_remain();
			$time_finish = microtime_float();
			$time_use = round($time_finish-$time_start, 5);
			echo 'document.getElementById("sms_server_speed").innerHTML = "本服务器当前网络延迟：'.$time_use.' s";';
			echo 'document.getElementById("sms_account_status").innerHTML = "'.$result['status'].'，短信剩余：'.$result['remain'].'";';
			return;
		}
		else
		{
			$action = '?mod=tttuangou&code=smsconfig&op=submit';
			$sms = ConfigHandler::get('sms');
			include(handler('template')->file('@admin/tttuangou_smsconfig'));
		}
	}

	function Mainpay(){
		extract($this->Get);
		extract($this->Post);
		$sql='select * from '.TABLE_PREFIX.'tttuangou_payment order by `order` asc';
		$query = $this->DatabaseHandler->Query($sql);
		$pay=$query->GetAll();
		include(handler('template')->file('@admin/tttuangou_paylist'));
	}
	function Onlinepay(){
		extract($this->Get);
		extract($this->Post);
		$sql='update '.TABLE_PREFIX.'tttuangou_payment  set  enabled = if(enabled="true", "false", "true") where id = '.$id;
		$query = $this->DatabaseHandler->Query($sql);
		$this->Messager("修改成功!");
	}
	function Setpay(){
		extract($this->Get);
		extract($this->Post);
		$sql='select * from '.TABLE_PREFIX.'tttuangou_payment where id = '.$id;
		$query = $this->DatabaseHandler->Query($sql);
		$pay=$query->Getrow();
		$cfg_value=unserialize($pay['config']);
		$action='?mod=tttuangou&code=dosetpay';
		include(handler('template')->file('@admin/pay_'.$pay['code']));
	}
	function Dosetpay(){
		extract($this->Get);
		extract($this->Post);
				$ary=array(
			'detail' => $desc,
			'order' => $order,
			'config' => serialize($cfg_value),
		);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_payment');
		$result=$this->DatabaseHandler->Update($ary,'id='.$mid);
		$this->Messager("修改成功","?mod=tttuangou&code=mainpay");
	}

	function Ticketz(){
		$keyword=$this->Post['keyword']==''?$this->Get['keyword']:$this->Post['keyword'];
		$searchtype=$this->Post['type']==''?$this->Get['type']:$this->Post['type'];
		$time=$this->Post['time']==''?$this->Get['time']:$this->Post['time'];
		$used=$this->Post['used']==''?$this->Get['used']:$this->Post['used'];
		$addsql='';
		if($keyword!='' || $time!='' || $used!='' ){
			$addsql=' where 1 ';
			switch ($searchtype)
			{
				case '1': $type = 't.number';break;
				case '2': $type = 'm.username';break;
				case '3': $type = 'p.name';break;
			}
			if($keyword!='')$addsql.= ' and '.$type.' LIKE \'%'.$keyword.'%\'';
			if($time!='')
			{
				$time = strtotime($time);
				$time2 = $time+86400;
				$addsql.= ' and p.perioddate BETWEEN '.$time.' and '.$time2.'';
			}
			if($used!='false')$addsql.= ' and t.status = '.$used.'';
		}
		$page=intval($_REQUEST['page'])==false?1:intval($_REQUEST['page']);
		$sql='SELECT count(*) FROM '.TABLE_PREFIX.'tttuangou_ticket t LEFT JOIN '.TABLE_PREFIX.'tttuangou_product p ON t.productid=p.id left join '.TABLE_PREFIX.'system_members m on t.uid = m.uid '.$addsql;
		$query = $this->DatabaseHandler->Query($sql); 
		$num=$query->GetRow();
		$num=$num['count(*)'];
		if($addsql!='' && $num==0)$this->Messager("符合该条件的团购券找不到",'?mod=tttuangou&code=ticket');
		$pagenum=30;		$page_arr = page($num,$pagenum,$query_link,$_config);

		$sql='SELECT t.*,p.name,m.username,p.perioddate FROM '.TABLE_PREFIX.'tttuangou_ticket t LEFT JOIN '.TABLE_PREFIX.'tttuangou_product p ON t.productid=p.id left join '.TABLE_PREFIX.'system_members m on t.uid = m.uid '.$addsql.' order by ticketid DESC limit '.($page-1)*$pagenum.','.$pagenum;
		$query = $this->DatabaseHandler->Query($sql);
		$ticket=$query->GetAll();

		include(handler('template')->file('@admin/tttuangou_ticket'));
	}
	function Deleteticket(){
		extract($this->Get);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_ticket');
		$result=$this->DatabaseHandler->Delete('','ticketid='.intval($ticketid));
		$this->Messager("删除成功",'?mod=tttuangou&code=ticket');
	}

	function ExpressMain()
	{
		extract($this->Get);
		switch ($op)
		{
			case 'addressview':
				$address = logic('address')->GetOne($aid);
				$order = logic('order')->GetOne($oid);
				$express = logic('express')->SrcOne($order['expresstype']);
				include(handler('template')->file('@admin/iframe_express'));
				exit;
			case 'sign':
				extract($this->Post);
				logic('order')->Update($orderid, array('invoice'=>$invoice));
				logic('pay')->SendGoods(logic('order')->GetOne($orderid));
				echo '登记完成，请关闭窗口！';
				exit;
			default:
				$page=intval($_GET['page'])==false?1:intval($_GET['page']);
				$pagenum = 20; 								$op = $this->Get['op'];
				$link = '';
				if ('search' == $op)
				{
					$byway = isset($_GET['byway']) ? $_GET['byway'] : $_POST['byway'];
					$bywhat = isset($_GET['bywhat']) ? $_GET['bywhat'] : $_POST['bywhat'];
					if ($byway == 'name')
					{
						$sql_search = 'AND p.name LIKE "%'.$bywhat.'%"';
					}
					elseif ($byway == 'username')
					{
						$sql_search = 'AND m.username="'.$bywhat.'"';
					}
					elseif ($byway == 'orderid')
					{
						$sql_search = 'AND o.orderid="'.$bywhat.'"';
					}
					$link = '&op=search&byway='.$byway.'&bywhat='.$bywhat;
				}
								$page = ($sql_search!='')?0:$page;
				$pagenum = ($sql_search!='')?0:$pagenum;
				if ($switch == '' || $switch == 'wait')
				{
					$rs_count = ($sql_search!='')?0:logic('order')->Count('process="WAIT_SELLER_SEND_GOODS"');
					$switch = 'wait';
										$list = logic('order')->GetList(0, -1, -1, 'process="WAIT_SELLER_SEND_GOODS"');
					$actionSwitchURL = '?mod=tttuangou&code=express&switch=sent';
					$actionSwitchName = '点此查看所有 已发货 的订单';
				}
				else
				{
					$rs_count = ($sql_search!='')?0:logic('order')->Count('process="WAIT_BUYER_CONFIRM_GOODS"');
					$switch = 'sent';
										$list = logic('order')->GetList(0, -1, -1, 'process="WAIT_BUYER_CONFIRM_GOODS"');
					$actionSwitchURL = '?mod=tttuangou&code=express&switch=wait';
					$actionSwitchName = '点此查看所有 待发货 的订单';
				}
				$page_arr = page($rs_count, $pagenum, 'admin.php?mod=tttuangou&code=express&switch='.$switch.$link);
				$action = 'admin.php?mod=tttuangou&code=express&switch='.$switch.'&op=search';
				include(handler('template')->file('@admin/tttuangou_express'));
		}
	}

	function ExpressManager()
	{
		$opid = $_POST['op_id'];
		if ($opid != '')
		{
			$this->MeLogic->expressRemove($opid);
			$this->Messager('删除操作成功！');
		}
		$action='?mod=tttuangou&code=expressmanage';
		$list = $this->MeLogic->expressList();
		include(handler('template')->file('@admin/tttuangou_express_manage'));
	}

	function Mainquestion(){
		$page=intval($_REQUEST['page'])==false?1:intval($_REQUEST['page']);
		$sql='SELECT count(*) FROM '.TABLE_PREFIX.'tttuangou_question';
		$query = $this->DatabaseHandler->Query($sql); 
		$num=$query->GetRow();
		$num=$num['count(*)'];
		$pagenum=30;		$page_arr = page($num,$pagenum,$query_link,$_config);

		$sql='select * from '.TABLE_PREFIX.'tttuangou_question order by time desc limit '.($page-1)*$pagenum.','.$pagenum;
		$query = $this->DatabaseHandler->Query($sql); 
		$question=$query->GetAll();
		include(handler('template')->file("@admin/tttuangou_question"));
	}
	function Replyquestion(){
		extract($this->Get);
		extract($this->Post);
		$sql='select * from '.TABLE_PREFIX.'tttuangou_question where id = '.$id;
		$query = $this->DatabaseHandler->Query($sql);
		$action='?mod=tttuangou&code=doreplyquestion'; 
		$reply=$query->GetROW();
		if($reply==''){
			$this->Messager("找不到该提问!");
		};
		include(handler('template')->file("@admin/tttuangou_reply"));
	}
	function Doreplyquestion(){
		extract($this->Get);
		extract($this->Post);
		$id=intval($id);
		if($id==false)$this->Messager("参数错误!");
		$ary=array(
			'reply' => $reply,
		);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_question');
		$result=$this->DatabaseHandler->Update($ary,'id='.$id);
		$ask = dbc(DBCMax)->select('question')->where('id='.$id)->limit(1)->done();
		$ask['reply'] = $reply;
		notify($ask['userid'], 'list.ask.reply', $ask);
		$this->Messager("操作成功","?mod=tttuangou&code=mainquestion");
		exit;
	}
	
	function Deletequestion(){
		$id=intval($this->Get['id']);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_question');
		$result=$this->DatabaseHandler->Delete('','id='.$id);
		$this->Messager($return ? $return : "操作成功","?mod=tttuangou&code=mainquestion");
	}

	function Mainfinder(){
		$page=intval($_REQUEST['page'])==false?1:intval($_REQUEST['page']);
		$sql='SELECT count(*) FROM '.TABLE_PREFIX.'tttuangou_finder';
		$query = $this->DatabaseHandler->Query($sql); 
		$num=$query->GetRow();
		$num=$num['count(*)'];
		$pagenum=15;		$page_arr = page($num,$pagenum,$query_link,$_config);

		$sql='select f.*,p.flag from '.TABLE_PREFIX.'tttuangou_finder f left join '.TABLE_PREFIX.'tttuangou_product p on p.id=f.productid   order by id desc limit '.($page-1)*$pagenum.','.$pagenum;
		$query = $this->DatabaseHandler->Query($sql); 
		$finder=$query->GetAll();
				if(!empty($finder)){
			$sql='select uid,truename,username,lastip from '.TABLE_PREFIX.'system_members';
			$query = $this->DatabaseHandler->Query($sql); 
			$user=$query->GetAll();
			$newuser=array();
			foreach($user as $value){
				$newuser[$value['uid']]=$value;
			};
		}
		unset($user);
		include(handler('template')->file("@admin/tttuangou_finder"));
	}
	
	function Yesfinder(){
		extract($this->Get);
		$this -> config=ConfigHandler::get('product');
				$sql='select * from '.TABLE_PREFIX.'tttuangou_finder where id = '.$id;
		$query = $this->DatabaseHandler->Query($sql); 
		$finder=$query->GetRow();
		if($finder=='' || $finder['status']!=1){
			$this->Messager('返利出现错误！');
		};
				logic('me')->money()->add($this->config['default_payfinder'],$finder['finderid'], array(
			'name' => '邀请返利',
			'intro' => '您获得邀请返利'.$this->config['default_payfinder'].'元'
		));
				$sql='update '.TABLE_PREFIX.'tttuangou_finder set status = 2 where id = '.$id;
		$query = $this->DatabaseHandler->Query($sql);
		$this->Messager("已经通过验证，并返利".$this->config['default_payfinder']."元！");
	}
	
	function Nofinder(){
		extract($this->Get);
				$sql='update '.TABLE_PREFIX.'tttuangou_finder set status = 0 where id = '.$id;
		$query = $this->DatabaseHandler->Query($sql);
		$this->Messager("取消成功！"); 
	}
	
	function Deletefinder(){
		extract($this->Get);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_finder');
		$result=$this->DatabaseHandler->Delete('','id='.intval($id));
		$this->Messager("操作成功",'?mod=tttuangou&code=mainfinder');
	}
	
	function Usermsg(){
		$page=intval($_REQUEST['page'])==false?1:intval($_REQUEST['page']);
		$sql='SELECT count(*) FROM '.TABLE_PREFIX.'tttuangou_usermsg';
		$query = $this->DatabaseHandler->Query($sql); 
		$num=$query->GetRow();
		$num=$num['count(*)'];
		$pagenum=15;		$page_arr = page($num,$pagenum,$query_link,$_config);
		$sql='select `id`,`name`,`time`,`type`,`readed` FROM '.TABLE_PREFIX.'tttuangou_usermsg order by `time` desc limit '.($page-1)*$pagenum.','.$pagenum;
		$query = $this->DatabaseHandler->Query($sql); 
		$usermsg=$query->GetAll();
		include(handler('template')->file("@admin/tttuangou_usermsg"));
	}
	
	function Readusermsg(){
		$sql='select * from '.TABLE_PREFIX.'tttuangou_usermsg where `id` = '.intval($this->Get['id']);
		$query = $this->DatabaseHandler->Query($sql); 
		$msg=$query->GetRow();
		if($msg['readed']==0){
			$ary=array(
				'readed'=>1,
			);
			$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_usermsg');
			$result=$this->DatabaseHandler->Update($ary,'id='.$msg['id']);
		}
		if($msg=='')$this->Messager("该信息不存在!");
		include(handler('template')->file("@admin/tttuangou_readusermsg"));
	}
	function Deleteusermsg(){
		extract($this->Get);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_usermsg');
		$result=$this->DatabaseHandler->Delete('','id='.intval($id));
		$this->Messager("操作成功",'?mod=tttuangou&code=usermsg');
	}

		function DataClear()
	{
		if (!isset($_GET['confirm']) || $_GET['confirm'] == '')
		{
			$action='?mod=tttuangou&code=clear&confirm=true';
			if (file_exists (DATA_PATH.'data.clear.lock'))
			{
				$clear_locked = true;
			}
			else
			{
				$clear_locked = false;
			}
			include(handler('template')->file('@admin/tttuangou_clear'));
		}
		else
		{
			if (file_exists (DATA_PATH.'data.clear.lock'))
			{
				$this->Messager('初始化功能已锁定，操作失败！');
				return;
			}
						$dataMap = array(
				'tuan'=> array('tttuangou_product', 'tttuangou_order', 'tttuangou_order_clog', 'tttuangou_paylog', 'tttuangou_uploads', 'tttuangou_ticket', 'tttuangou_express', 'tttuangou_finder', 'tttuangou_seller'),
				'market'=> array('tttuangou_subscribe'),
				'log'=> array('system_failedlogins', 'system_log', 'system_robot_log', 'task_log'),
				'mail'=> array('tttuangou_push_queue', 'tttuangou_push_log', 'tttuangou_push_template'),
				'talk'=> array('tttuangou_question', 'tttuangou_usermsg')
			);
						foreach ($_POST as $key => $val)
			{
				if (substr($key, 0, 4) == 'data')
				{
					$mid = substr($key, 5);
					if ('' != $dataMap[$mid])
					{
												foreach ($dataMap[$mid] as $i => $tableName)
						{
							$sql = 'TRUNCATE TABLE  `'.TABLE_PREFIX.''.$tableName.'`';
							$this->DatabaseHandler->Query($sql);
						}
						if ('tuan' == $mid)
						{
														$this->__clear_upload_image();
						}
					}
				}
			}
						file_put_contents(DATA_PATH.'data.clear.lock', date('Y-m-d H:i:s', time()));
			$this->Messager('初始化完成！');
		}
	}
	function __clear_upload_image()
	{
				Load::lib('io');
		IoHandler::ClearDir(UPLOAD_PATH);
	}
		function KefuConfig()
	{
		$op = $_GET['op'];
		if ($op == 'save')
		{
			extract($this->Post);
			$kf['power'] = $power;
			$kf['qq'] = $qq;
			$kf['msn'] = $msn;
			$kf['aliww'] = $aliww;
			$kf['phone'] = $phone;
			ConfigHandler::set('kefu', $kf);
			$this->Messager('保存成功！');
		}
		$kf = ConfigHandler::get('kefu');
		$action = '?mod=tttuangou&code=kefuconfig&op=save';
		include(handler('template')->file('@admin/tttuangou_kefu_config'));
	}

		function EmailSends()
	{
		$op = $_GET['op'];
		$pid = $_GET['pid'];
		if ('' == $pid)
		{
			$this->Messager('请到产品管理页面选择一个产品进行订阅通知！现在正为您跳转...', 'admin.php?mod=tttuangou&code=listproduct', 3);
		}
		$action = 'admin.php?mod=tttuangou&code=emailsend&pid='.$pid.'&op=send';
		$product = $this->ProductLogic->GetOne($pid);
		if ($product['display'] == 2)
		{
			$cityId = 0;
			$city_name = '全国';
		}
		else
		{
			list($cityAry,$cityId,$city_name)=logic('misc')->City();
			if ($city_name == '全国')
			{
				$cityId = 0;
			}
		}
		$site_name = $this->Config['site_name'];
		$site_url = $this->Config['site_url'].'/';
					$product['url'] = $site_url.'?view='.$product['id'];
			$product['saves'] = $product['price'] - $product['nowprice'];
			$imx = explode('|', $product['img']);
			$product['img'] = $site_url.IMAGE_PATH.'product/'.$imx[0];
				$base_url = $this->Config['site_url'].'/templates/default/mail/';
		$weekdays = array('','一','二','三','四','五','六','天');
		$mail = array();
		$set = ConfigHandler::get('product');
		$mail['address'] = $set['default_mail_from'];
		$mail['city'] = $city_name;
		$mail['title'] = '['.$this->Config['site_name'].']['.$city_name.'] '.$product['name'];

		if ('preview' == $op)
		{
			include ($this->TemplateHandler->Template('mail/style_default'));
			exit;
		}
		elseif ('send' == $op)
		{
			$citysend = $_GET['cityid'];
						$sql='select * from '.TABLE_PREFIX.'tttuangou_email';
			if(intval($citysend)!=0)
			{
				$sql.=' where city = '.$citysend;
			};
			$query = $this->DatabaseHandler->Query($sql);
			$email=$query->GetAll();

			
			ob_end_clean(); 			ob_start(); 				include ($this->TemplateHandler->Template('mail/style_default'));
				$mail['content'] = ob_get_clean(); 			ob_end_clean(); 			ob_start('my_output'); 
			foreach($email as $value)
			{
					$ary=array(
						'address'=> $value['email'],
						'username'=>'尊敬的用户',
						'title'=>$mail['title'],
						'content'=>$mail['content'],
						'addtime'=>time()
					);
					$keys=$values='';
					foreach($ary as $i => $valuez)
					{
						$a=$i=='addtime'?"":',';
						$keys.='`'.$i.'`'.$a;
						$values.='\''.$valuez.'\''.$a;
					}
					$sql='insert into '.TABLE_PREFIX.'tttuangou_cron ('.$keys.') VALUES ('.$values.')';
					$this->DatabaseHandler->Query($sql);
			}
			$this->Messager("邮件已写入计划任务表，将适时发送！",'?mod=tttuangou&code=listproduct');
		}
		include (handler('template')->file('@admin/tttuangou_email_sends'));
	}

		function MailCronManager()
	{
		$op = $_GET['op'];
		if ('del' == $op)
		{
						$id = $_GET['id'];
			$sql = 'DELETE FROM '.TABLE_PREFIX.'tttuangou_cron WHERE id='.$id;
			$this->DatabaseHandler->Query($sql);
			$this->Messager('删除操作成功！');
		}
		elseif ('dels' == $op)
		{
						$time_calc = intval($_POST['timec']);
			$time_unit = $_POST['timeu'];
			$time_rule = array('s'=>1,'m'=>60,'h'=>3600);
			$time_delete_in = time() - ( $time_calc * $time_rule[$time_unit] );
			$sql = 'DELETE FROM '.TABLE_PREFIX.'tttuangou_cron WHERE addtime>='.$time_delete_in;
			$this->DatabaseHandler->Query($sql);
			$affect = $this->DatabaseHandler->AffectedRows();
			$this->Messager('删除操作成功！共有 '.$affect.' 条记录被删除');
		}
		$page=intval($_GET['page'])==false?1:intval($_GET['page']);
		$pagenum = 20; 					$c_sql = 'SELECT COUNT(*) FROM '.TABLE_PREFIX.'tttuangou_cron';
			$c_result = $this->DatabaseHandler->Query($c_sql)->GetRow();
			$num = $c_result['COUNT(*)'];
		$page_arr = page($num, $pagenum, 'admin.php?mod=tttuangou&code=mailcron');
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'tttuangou_cron ORDER By addtime DESC limit '.($page-1)*$pagenum.','.$pagenum;
		$query = $this->DatabaseHandler->Query($sql);
		if ($query)
		{
			$cron = $query->GetAll();
		}
		include(handler('template')->file('@admin/tttuangou_mailcron'));
	}
	function MailCronWorks()
	{
		$op = $_GET['op'];
		if ('run' == $op)
		{
						$taskFile = TASK_PATH.'mail_send.task.php';
			include_once $taskFile;
			$TaskItem = new TaskItem();
			$sends = $TaskItem->run();
			if ($sends > 0)
			{
				echo $sends;
			}
			else
			{
				echo '发送邮件时出错，请检查您的邮件配置！';
			}
			return;
		}
		$c_sql = 'SELECT COUNT(*) FROM '.TABLE_PREFIX.'tttuangou_cron';
		$c_result = $this->DatabaseHandler->Query($c_sql)->GetRow();
		$cronLength = $c_result['COUNT(*)'];
		if ($cronLength == 0)
		{
			$this->Messager('没有需要发送的邮件！请返回');
		}
		include(handler('template')->file('@admin/tttuangou_mailcron_works'));
	}
	function DataAPI()
	{
		global $rewriteHandler;
		include_once INCLUDE_PATH.'rewrite.php';
		$url_pre = '/?mod=apiz&code=js';
		if ($rewriteHandler)
		{
			$url_pre = $rewriteHandler->formatURL($url_pre);
		}
		$script_url = $this->Config['site_url'].$url_pre;
		if ($this->OPC == 'demo')
		{
			include handler('template')->file('@admin/tttuangou_data_api_demo');
		}
		else
		{
			include(handler('template')->file('@admin/tttuangou_data_api'));
		}
	}
	function ImageThumbRebuild()
	{
				Load::lib('io');
		$o_dirs = IoHandler::ReadDir(IMAGE_PATH.'product/');
		$dirs = array();
		foreach ($o_dirs as $i => $dir)
		{
			if (preg_match('/product\/\d{4}-\d{2}-\d{2}/', $dir))
			{
				$dirs[] = $dir;
			}
		}
		$thumbwidth = $this->Config['thumbwidth'];
		$thumbheight = $this->Config['thumbheight'];
				$op = $_GET['op'];
		if ($op == 'run')
		{
			$od = $_GET['od'];
			$dir = $dirs[$od];
			$files = IoHandler::ReadDir($dir);
			foreach ($files as $i => $src_file)
			{
				$dst_file = str_replace('/product/', '/product/s-', $src_file);
				resize_image($src_file, $dst_file, $thumbwidth, $thumbheight);
			}
			echo '更新了目录[ '.$dir.' ]，有[ <b>'.count($files).'</b> ]张缩略图被生成！';
			return;
		}
		$cronLength = count($dirs);
		include(handler('template')->file('@admin/tttuangou_imagethumb_rebuild'));
	}
	function IndexNaviManager()
	{
		$action = '?mod=tttuangou&code=doindexnav&op=modify';
		$navs = ConfigHandler::get('nav');
		include(handler('template')->file('@admin/tttuangou_list_nav'));
	}
	function doIndexNaviManager()
	{
		$op = $this->Get['op'];
		if ('modify' == $op)
		{
			$list = $this->Post;
						$order = $list['order'];
			foreach ($order as $i => $oid)
			{
				if ($oid != '')
				{
					$sort[$oid] = $i;
				}
			}
						ksort($sort);
						foreach ($sort as $oid => $i)
			{
				$one = array();
				$one['order'] = $list['order'][$i];
				$one['name'] = $list['name'][$i];
				$one['url'] = $list['url'][$i];
				$one['title'] = $list['title'][$i];
				$one['target'] = $list['target'][$i];
				$set[] = $one;
			}
						ConfigHandler::set('nav', $set);
			$this->Messager('保存成功！');
		}
	}
	function DefaultStyleManager()
	{
		$TPL_DIR = ROOT_PATH.$this->Config['template_root_path'];
		$styles = array();
		$styles[1] = array(
			'name' => '默认风格',
			'preview' => $TPL_DIR.'default/preview.gif',
			'flag' => 'styles0'
		);
		$names = array('','','蓝色风格','喜庆风格','水晶风格','绿色风格');
		for ($i=2; $i<=5; $i++)
		{
			$styles[$i] = array(
				'name' => $names[$i],
				'preview' => $TPL_DIR.'tpl_'.$i.'/preview.gif',
				'flag' => 'styles'.($i-1)
			);
		}
		$set = ConfigHandler::get('product');
		$default_style = $set['default_style'];
		include(handler('template')->file('@admin/tttuangou_default_style'));
	}
	function doDefaultStyleManager()
	{
		$op = $this->Get['op'];
		if ($op == 'set')
		{
			$id = $this->Get['id'];
			$id = (int)$id - 1;
			$set = ConfigHandler::get('product');
			$set['default_style'] = 'styles'.$id;
			ini('product', $set);
			$this->Messager('设置已完成！');
		}
	}
	function SiteLogoManager()
	{
		$TPL_DIR = ROOT_PATH.$this->Config['template_root_path'];
		$logos = array();
		$logos[1] = array(
			'title' => '默认风格',
			'url' => $TPL_DIR.'default/images/logo.png',
		);
		
		
		$logos[] = array(
			'title' => '新产品邮件推广',
			'url' => $TPL_DIR.'html/push/mail/logo.gif',
		);
		$logos[] = array(
			'title' => '团购指南页面“团购券示例”',
			'url' => $TPL_DIR.'default/images/buy_yhq.png',
		);
		include(handler('template')->file('@admin/tttuangou_site_logo'));
	}
	function doSiteLogoManager()
	{
		$op = $this->Get['op'];
		if ($op == 'save')
		{
						if (is_array($_FILES['uploads']['name']))
			{
				$FILES_O = $_FILES;
				$_FILES = array();
				$loopc = count($FILES_O['uploads']['name']);
				for ($i=0; $i<$loopc; $i++)
				{
					if ($FILES_O['uploads']['name'][$i] != '')
					{
						break;
					}
				}
			}
			else
			{
				$this->Messager('出错了！');
			}
			$_FILES['uploads']['name'] = $FILES_O['uploads']['name'][$i];
			$_FILES['uploads']['type'] = $FILES_O['uploads']['type'][$i];
			$_FILES['uploads']['tmp_name'] = $FILES_O['uploads']['tmp_name'][$i];
			$_FILES['uploads']['error'] = $FILES_O['uploads']['error'][$i];
			$_FILES['uploads']['size'] = $FILES_O['uploads']['size'][$i];
			if ('' == $_FILES['uploads']['name'])
			{
				$this->Messager('请选择要上传的图片！');
			}
			$default_type=array('jpg','pic','png','jpeg','bmp','gif'); 			$imgary=explode('.',$_FILES['uploads']['name']);
			if(!in_array(strtolower($imgary[count($imgary)-1]),$default_type)){
				$this->Messager('不允许上传的图片格式！');
			}
			$full_path = urldecode($this->Get['path']);
			$fp_ary = explode('/', $full_path);
			$file = $fp_ary[count($fp_ary)-1];
			$dir = '';
			for ($i=0;$i<count($fp_ary)-1;$i++)
			{
				if ($fp_ary[$i] != '.')
				{
					$dir .= $fp_ary[$i].'/';
				}
			}
			$files = logic('upload')->Save('uploads', $dir.$file);
			if ($files['error'])
			{
				$this->Messager( $files['msg'] );
			}
			else
			{
				$this->Messager('保存成功！');
			}
		}
	}
	function ShareConfig()
	{
		$op = $this->Get['op'];
		if($op == 'modify')
		{
			$list = $this->Post;
						$order = $list['order'];
			foreach ($order as $i => $oid)
			{
				if ($oid != '')
				{
					$sort[$oid] = $i;
				}
			}
						ksort($sort);
						foreach ($sort as $oid => $i)
			{
				$flag = $list['flag'][$i];
				$one = array();
				$one['order'] = $list['order'][$i];
				$one['name'] = $list['name'][$i];
				$one['display'] = (isset($list['display'][$flag]) && $list['display'][$flag] == 'on') ? 'yes' : 'no';
				$set[$flag] = $one;
			}
						$bshare = ini('share.~@bshare');
						$bshare_POST = post('bshare');
			$bshare['uuid'] = $bshare_POST['uuid'];
						$set['~@bshare'] = $bshare;
			ini('share', $set);
						$this->Messager('保存成功！');
		}
		$listAll = array('link', 'qzone', 'kaixin001', 'renren', 'douban', 'tsina', 'bai', 'gmail', 'delicious', 'digg', 'yahoo', 'google', 'facebook', 'twitter', 'baiduhi', 'blogbus', 'clipboard', 'qqmb', 'qqxiaoyou', 'xianguo');
		$action = '?mod=tttuangou&code=shareconfig&op=modify';
		$shares = ConfigHandler::get('share');
				foreach ($listAll as $i => $flag)
		{
			if (!array_key_exists($flag, $shares))
			{
				$shares[$flag] = array(
					'order' => '',
					'name' => '',
					'display' => 'no'
				);
			}
		}
		if (isset($shares['~@bshare']))
		{
			$bshare = $shares['~@bshare'];
			unset($shares['~@bshare']);
		}
		include(handler('template')->file('@admin/tttuangou_list_share'));
	}
}
?>