<?php

/**
 * 模块：产品管理
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name product.mod.php
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
	function Main()
	{
		header('Location: ?mod=product&code=vlist');
	}
	function vList()
	{
		logic('product')->Maintain();
				$filter = '1';
		$prosta = get('prosta', 'number');
		is_numeric($prosta) && $filter .= ' AND p.status='.$prosta;
		$prodsp = get('prodsp', 'number');
		is_numeric($prodsp) && $filter .= ' AND p.display='.$prodsp;
				$list = logic('product')->GetList(-1, null, $filter);
		logic('product')->AVParser($list);
				$drfCount = logic('product')->GetDraftCount();
				include handler('template')->file('@admin/product_list');
	}
	function Add()
	{
		$p = array();
		$p['successnum'] = ini('product.default_successnum');
		$p['virtualnum'] = ini('product.default_virtualnum');
		$p['oncemax'] = ini('product.default_oncemax');
		$p['oncemin'] = ini('product.default_oncemin');
		include handler('template')->file('@admin/product_mgr');
	}
	function Add_image()
	{
		$pid = get('pid', 'int');
		$id = get('id', 'int');
		$p = logic('product')->SrcOne($pid);
		$imgs = explode(',', $p['img']);
		foreach ($imgs as $i => $iid)
		{
			if ($iid == '' || $iid == 0)
			{
				unset($imgs[$i]);
			}
		}
		$imgs[] = $id;
		$new = implode(',', $imgs);
		logic('product')->Update($pid, array('img'=>$new));
		exit('ok');
	}
	function Edit()
	{
		$id = get('id', 'int');
		$did = get('draftID', 'int');
		$queryID = $did ? $did : $id;
		$p = logic('product')->GetOne($queryID);
		$p || exit('PID Invalid');
		$p['id'] = $id;
		$draft = logic('product')->CheckProductDraft($id);
		include handler('template')->file('@admin/product_mgr');
	}
	function Save()
	{
		$data = array();
		$data['name'] = post('name', 'txt');
		$data['flag'] = post('flag', 'txt');
		$data['flag'] || $data['flag'] = $data['name'];
		$data['city'] = post('city', 'int');
		$data['display'] = post('display', 'int');
		$data['sellerid'] = post('sellerid', 'int');
		$data['intro'] = post('intro');
		$data['order'] = post('order', 'int');
		$data['content'] = post('content');
		$data['cue'] = post('cue');
		$data['theysay'] = post('theysay');
		$data['wesay'] = post('wesay');
		$data['price'] = post('price', 'float');
		$data['nowprice'] = post('nowprice', 'float');
		$data['maxnum'] = post('maxnum', 'int');
		$data['begintime'] = strtotime(post('begintime'));
		$data['overtime'] = strtotime(post('overtime'));
		$data['type'] = post('type', 'txt');
		$data['perioddate'] = strtotime(post('perioddate'));
		$data['allinone'] = post('allinone', 'txt');
		$data['weight'] = post('weight', 'int');
		$data['weight'] *= (post('weightunit', 'txt') == 'g') ? 1 : 1000;
		$data['successnum'] = post('successnum', 'int');
		$data['successnum'] < 1 && $data['successnum'] = 1;
		$data['virtualnum'] = post('virtualnum', 'int');
		$data['oncemax'] = post('oncemax', 'int');
		$data['oncemin'] = post('oncemin', 'int');
		$data['multibuy'] = post('multibuy', 'txt');
				$data['saveHandler'] = post('saveHandler', 'txt') == 'draft' ? 'draft' : 'normal';
		$isDraft = $data['saveHandler'] == 'draft';
		$draftID = post('draftID', 'int');
		$data['draft'] = $isDraft ? $draftID : 0;
				$noNULL = $isDraft ?  array() : array(
			'name' => '产品名',
			'city' => '产品投放城市',
			'sellerid' => '产品所属商家',
			'price' => '产品原价',
			'nowprice' => '产品现价'
		);
		foreach ($noNULL as $key => $name)
		{
			if ($key == 'nowprice' && is_numeric($data[$key])) continue;
			if (!$data[$key])
			{
				$this->Messager('【'.$name.'】不能为空！', -1);
			}
		}
				if (post('imgs') != '')
		{
			$data['img'] = substr(post('imgs', 'txt'), 0, -1);
		}
				logic('catalog')->ProUpdate($data);
		
		logic('city')->product_on_save($data);
				if ($data['type'] == 'prize')
		{
						$data['successnum'] = $data['successnum'] > $data['virtualnum'] ? $data['virtualnum'] : $data['successnum'];
						$data['multibuy'] = 'false';
		}
				$id = post('id', 'int');
		if ($id == 0)
		{
			$data['addtime'] = time();
			$data['status'] = PRO_STA_Normal;
			$id = logic('product')->Publish($data);
		}
		else
		{
						$data['@extra'] = array(
				'category' => post('__catalog_subclass', 'int'),
				'hideseller' => post('hideseller', 'txt'),
				'irebates' => post('irebates', 'txt'),
				'expresslist' => post('expresslist'),
				'specialPayment' => post('specialPayment', 'txt'),
				'specialPaymentSel' => post('specialPaymentSel') ? (implode(',', post('specialPaymentSel')).',') : ''
			);
			$allow2CSaveHandler = logic('product')->allowCSaveHandler($id, $data['saveHandler']);
			if ($allow2CSaveHandler)
			{
				logic('product')->Update($id, $data);
			}
			else
			{
				zlog('product')->saveError($id, '非法的草稿保存请求！');
				$alert = '保存失败！! 草稿源数据已被删除或者非法的草稿保存请求！';
				if ($isDraft)
				{
					exit(jsonEncode(array('status'=>'failed','msg'=> $alert)));
				}
				{
					$this->Messager($alert, -1);
				}
			}
		}
				$hideSeller = post('hideseller', 'txt');
		if ($hideSeller == 'true')
		{
			meta('p_hs_'.$id, 'yes');
		}
		else
		{
			meta('p_hs_'.$id, null);
		}
				$inviteRebates = post('irebates', 'txt');
		if ($inviteRebates == 'true')
		{
			meta('p_ir_'.$id, 'yes');
		}
		else
		{
			meta('p_ir_'.$id, null);
		}
				if (post('expresslist', 'trim') != '')
		{
			meta('expresslist_of_'.$id, post('expresslist'));
		}
		else
		{
			meta('expresslist_of_'.$id, null);
		}
				$specialPayment = post('specialPayment', 'txt');
		if ($specialPayment == 'true')
		{
			$paymentSel = post('specialPaymentSel');
			if ($paymentSel)
			{
				$listString = '';
				foreach ($paymentSel as $i => $pCode)
				{
					$listString .= $pCode.',';
				}
				meta('paymentlist_of_'.$id, $listString);
			}
		}
		else
		{
			meta('paymentlist_of_'.$id, null);
		}
				$notifyType = post('notifyType', 'txt');
		if ($notifyType != '-1')
		{
			if (ini('notify.api.'.$notifyType.'.enabled'))
			{
				meta('p_nt_'.$id, $notifyType);
			}
			else
			{
				meta('p_nt_'.$id, null);
			}
		}
		else
		{
			meta('p_nt_'.$id, null);
		}
				logic('product')->PresellSubmit($id);
				logic('attrs')->ProductSubmit($id);
				logic('gps')->seller_linker($data['sellerid'], $id);
				$isDraft || logic('product')->Maintain($id);
		$isDraft && exit(jsonEncode(array('status'=>'ok','pid'=>$id)));
		logic('product')->ClearDraft($id, $draftID);
		$this->Messager('产品数据更新完成！', '?mod=product&code=vlist');
	}
	function Save_intro()
	{
		$id = get('id', 'int');
		$intro = get('intro', 'txt');
		logic('upload')->Field($id, 'intro', $intro);
		exit('ok');
	}
	function Draft_restore()
	{
		$pid = get('pid', 'int');
		$did = get('did', 'int');
		logic('product')->ClearDraft($pid, $did, $did);
		exit('admin.php?mod=product&code=edit&id='.$pid.'&draftID='.$did.'&~iiframe=yes');
	}
	function Draft_list()
	{
		$list = logic('product')->GetDraftList();
		include handler('template')->file('@admin/product_draft_list');
	}
	function Draft_del()
	{
		$this->Draft_clear(false);
		$this->Messager('已经删除！');
	}
	function Draft_clear($exit = true)
	{
		$pid = get('pid', 'int');
		$did = get('did', 'int');
		logic('product')->ClearDraft($pid, $did);
		$exit && exit('ok');
	}
	function Del()
	{
		$id = get('id', 'int');
		logic('product')->Delete($id);
		$this->Messager('删除完成！');
	}
	function Del_image()
	{
		$pid = get('pid', 'int');
		$id = get('id', 'int');
		$p = logic('product')->SrcOne($pid);
		if ($p['img'] == '')
		{
						logic('upload')->Delete($id);
		}
		else
		{
			$imgs = explode(',', $p['img']);
			foreach ($imgs as $i => $iid)
			{
				if ($iid == $id)
				{
					logic('upload')->Delete($id);
					unset($imgs[$i]);
				}
			}
			$new = implode(',', $imgs);
			logic('product')->Update($pid, array('img'=>$new));
		}
		exit('ok');
	}
	function Quick_listCity()
	{
		$cid = get('icity', int);
		$list = array(
			array(
				'cityid' => 0,
				'cityname' => '请选择城市',
				'shorthand' => '__#__'
			)
		);
		$list = array_merge($list, logic('misc')->CityList());
		foreach ($list as $i => $one)
		{
			$sel = '';
			if ($one['cityid'] == $cid)
			{
				$sel = ' selected="selected"';
			}
			echo '<option value="'.$one['cityid'].'"'.$sel.'>'.$one['cityname'].'</option>';
		}
		exit;
	}
	function Quick_addCity()
	{
		$name = get('name', 'txt');
		$flag = get('flag', 'txt');
		$data = array(
			'cityname' => $name,
			'shorthand' => $flag,
			'display' => 1
		);
		dbc()->SetTable(table('city'));
		$r = dbc()->Insert($data);
		exit($r ? (string)$r : '添加失败！');
	}
	function Quick_addSeller()
	{
				$username = get('username', 'txt');
		$password = '123456';
		$rr = logic('seller')->Register($username, $password);
		$rr['error'] && exit($rr['result']);
		$uid = $rr['result'];
				$city = get('city', 'int');
		$sellername = get('sellername', 'txt');
		$sid = logic('seller')->Add($city, $uid, $sellername);
				exit($sid ? (string)$sid : '添加失败！');
	}
}

?>