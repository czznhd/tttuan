/** * @copyright (C)2011 Cenwor Inc. * @author Moyo <dev@uuland.org> * @package js * @name address.selector.js * @date 2011-09-28 17:19:19 */ var __a_s_new_loaded = false;

//$(document).ready(function(){
	$('input[name=address_id]').bind('click', function(){change_of_address_select()});
	$('#address_new select').bind('change', function(){region_loads($(this))});
	$('#address_submit_button').bind('click', function(){address_submit()});
	$('#address_new_form').validationEngine();
	$.hook.add('checkout_submit', function(){
		var addr = $('input[name=address_id]:checked').val();
		if (addr == 0 || addr == undefined)
		{
			$('input[name=address_id]:first').tipTip({
				content:"请选择一个有效的收货地址！",
				keepAlive:true,
				activation:"focus",
				defaultPosition:"top",
				edgeOffset:8,
				maxWidth:"300px"
			});
			$('input[name=address_id]:first').focus();
			df_allow_to_submit('address.selector', false);
		}
		else
		{
			checkout_field_append('address_id', addr);
			df_allow_to_submit('address.selector', true);
		}
	});
	// check default selected delay 1s
	setTimeout(change_of_address_select, 1000);
//});

function change_of_address_select()
{
	var addr = $('input[name=address_id]:checked').val();
	if (addr == undefined)
	{
		return;
	}
	if (addr == 0)
	{
		$.hook.call('address_rewrite');
		$('#address_new').slideDown();
		if (!__a_s_new_loaded) address_form_init();
	}
	else
	{
		$('#address_new').slideUp();
		$.hook.call('address_change', addr);
	}
}
function address_form_init()
{
	region_loads(null);
	__a_s_new_loaded = true;
}
function region_loads(obj)
{
	var tpl_select = '<option value="">请选择</option>';
	var tpl_loader = '<option value="">加载中</option>';
	if (obj == null)
	{
		$('#addr_province').html(tpl_loader);
		$('#addr_city').html(tpl_select);
		$('#addr_country').html(tpl_select);
		$.get('?mod=misc&code=region&parent=0', function(data){
			$('#addr_province').html(tpl_select+data);
		});
		return;
	}
	var id = obj.attr('id');
	if (id == 'addr_country') return;
	var parent = obj.val();
	if (parent == 0) return;
	if (id == 'addr_province')
	{
		$('#addr_city').html(tpl_loader);
		$('#addr_country').html(tpl_select);
		$.get('?mod=misc&code=region&parent='+parent, function(data){
			$('#addr_city').html(tpl_select+data);
		});
	}
	else if (id == 'addr_city')
	{
		$('#addr_country').html(tpl_loader);
		$.get('?mod=misc&code=region&parent='+parent, function(data){
			$('#addr_country').html(tpl_select+data);
		});
	}
}
function address_submit()
{
	var options = {
		beforeSubmit: function(){
			var checks = $('#address_new').validationEngine('validate');
			if (!checks) return false;
			$('#address_submit_button').attr('disabled', true);
		},
		url: '?mod=misc&code=address&op=save',
		success: function(data){
			eval('var data='+data);
			if (data.status != 'ok')
			{
				$('#address_submit_result').text(data.msg);
			}
			else
			{
				var li_radio = '';
				li_radio += $('#addr_name').val()+' - ';
				li_radio += $('#addr_province option:selected').text()+' ';
				li_radio += $('#addr_city option:selected').text()+' ';
				var li_radio_country = $('#addr_country option:selected').text();
				if (li_radio_country != '请选择')
				{
					li_radio += li_radio_country+' ';
				}
				li_radio += $('#addr_address').val()+' - ';
				li_radio += $('#addr_callphone').val();
				$('#li_address_new').before('<li><input type="radio" name="address_id" value="'+data.id+'" checked="checked" /> '+li_radio+'</li>');
				change_of_address_select();
			}
			$('#address_submit_button').attr('disabled', false);
		}
	};
	$('#address_new_form').ajaxSubmit(options);
}
