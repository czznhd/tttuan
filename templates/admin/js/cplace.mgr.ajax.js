/** * @copyright (C)2011 Cenwor Inc. * @author Moyo <dev@uuland.org> * @package js * @name cplace.mgr.ajax.js * @date 2013-07-12 18:10:30 */ var __CATA_MGR_DIALOG = null;

function __cplace_add(parentType, parentID)
{
	if (!parentID) parentID = 0;
	var opURL = 'admin.php?mod=city&code=place&op=add&parenttype='+parentType.toString()+'&parentid='+parentID.toString()+'&~iiframe=yes';
	__CATA_MGR_DIALOG = $.browser.msie ? window.open(opURL, 'cataMgr', 'width=500,height=220,toolbar=no,menubar=no,location=no,scrollbars=yes,status=no,resizable=no,left='+(screen.width-500)/2+',top='+(screen.height-260)/2+'') : art.dialog.open(opURL, {title:'区域添加'});
}

function __cplace_add_finish(id)
{
	if (id < 0)
	{
		$.notify.failed('添加失败！');
	}
	else
	{
		$.hook.call('cplace.add.finish', id);
	}
	__CATA_MGR_DIALOG.close();
}

function __cplace_del(id, callback)
{
	if (!confirm('确认删除吗？\n\n1 删除地区时会删除相关的街道！\n\n2 街道被删除时，其下的产品会变成无归属状态！')) return;
	$.get('admin.php?mod=city&code=place&op=del&id='+id.toString()+$.rnd.stamp(), function(data){
		if (data == 'ok')
		{
			callback(id);
		}
		else
		{
			$.notify.failed('删除失败！');
		}
	});
}