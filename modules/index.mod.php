<?php
/**
 * 模块：默认功能区
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name index.mod.php
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
		$clientUser = get('u', 'int');
		if ( $clientUser != '' )
		{
			handler('cookie')->setVar('finderid', $clientUser);
			handler('cookie')->setVar('findtime', time());
		}
		
		$data = logic('product')->display();
				if (!$data && get('page', 'int') == 0)
		{
			header('Location: '.rewrite('?mod=subscribe&code=mail'));
			exit;
		}
		$product = $data['product'];
		$this->Title = $data['mutiView'] ? '' : $product['name'];
		$data['mutiView'] || mocod('product.view');
		$data['mutiView'] || productCurrentView($product);
				include handler('template')->file($data['file']);
	}
	function ExpressConfirm()
	{
		$oid = $this->Get['id'];
		$result = $this->OrderLogic->orderExpressConfirm($oid);
		if ( $result )
		{
			$this->Messager(__('已经确认收货，本次交易完成！'), '?mod=me&code=order');
		}
		else
		{
			$this->Messager(__('无效的订单号！'), '?mod=me&code=order');
		}
	}
}
?>