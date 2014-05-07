/** * @copyright (C)2011 Cenwor Inc. * @author Moyo <dev@uuland.org> * @package js * @name buy.checkout.js * @date 2013-08-12 15:32:54 */ var _allow_to_submit = {};
var __reg_price_list = {};

$(document).ready(function(){
	
});

function fizinit()
{
	$('#num_buys').bind('blur', function(){change_of_num_buys()});
	change_of_num_buys();
	$('#checkout_submit').bind('submit', function(){return checkout_submit()});
}

function change_of_num_buys()
{
	var num = parseInt($('#num_buys').val());
	var tips = '';
	if (isNaN(num))
	{
		$('#num_buys').val(oncemin);
	}
	else if (num < oncemin)
	{
		tips = '您最少需要购买'+oncemin+'件商品才能参团！';
		$('#num_buys').val(oncemin);
	}
	else if (oncemax > 0 && num > oncemax)
	{
		tips = '您最多只能购买'+oncemax+'件商品！';
		$('#num_buys').val(oncemax);
	}
	else if (num > surplus)
	{
		tips = '本次团购只剩余'+surplus+'件商品了！';
		$('#num_buys').val(surplus);
	}
	if (tips != '')
	{
		$('#num_buys').tipTip({
			content:tips,
			keepAlive:true,
			activation:"focus",
			defaultPosition:"top",
			edgeOffset:8,
			maxWidth:"300px"
		});
		$('#num_buys').focus();
		num = parseInt($('#num_buys').val());
	}
	else
	{
		$.hook.call('buys_num_change', num);
	}
	__reg_price_list['product'] = {value : price*num};
	price_calc();
    if (typeof(weight) != 'undefined')
    {
        weight_calc(num);
    }
}
/**
 * 统计价格
 */
function price_calc(num)
{
	if (parseInt(num) > 0)
	{

	}
	else
	{
		num = parseInt($('#num_buys').val());
	}
	var total = 0;
	$.each(__reg_price_list, function(item, price) {
		if (!isNaN(parseFloat(price.value)))
		{
			var i_psv = price.csingle ? (parseFloat(price.value) * num) : parseFloat(price.value);
			$('#price_' + item).text(i_psv.toFixed(2));
			total += i_psv;
		}
	});
	$('#price_total').text(total.toFixed(2));
}
/**
* 计算重量
*/
function weight_calc(num)
{
    var wall = weight * num;
    var dsp =  wall>=1000?(Math.round(wall/10)/100)+' Kg':wall+' g';
    $('#product_weight').html(dsp);
}
/**
 * 注册价格计费种类
 * @param string id
 * @param string name
 * @param string calcMode
 */
function price_type_reg(id, name, calcMode)
{
	var fid = 'price_'+id;
	var price = __reg_price_list[id];
	if (price != undefined) return false;
	__reg_price_list[id] = {value : 0, csingle : calcMode == 'single' ? true : false};
	// 增加显示
	$('#tr_price_total').before('<tr id="tr_'+fid+'"><td class="left"><font id="'+fid+'_name">'+name+'</font></td><td class="right">&yen; <font id="'+fid+'" class="price">0</font></td></tr>');
}
/**
 * 检查计费名称是否存在
 * @param string id
 */
function price_type_exists(id)
{
	return __reg_price_list[id] == undefined ? false : true;
}
/**
 * 更改计费名称
 * @param string id
 * @param string newName
 * @param string calcMode
 */
function price_type_change(id, newName, calcMode)
{
	var fid = 'price_'+id+'_name';
	if ($('#'+fid).length < 1)
	{
		price_type_reg(id, newName, calcMode);
	}
	else
	{
		$('#'+fid).text(newName);
	}
}
/**
 * 删除计费名称
 * @param string id
 */
function price_type_remove(id)
{
	delete __reg_price_list[id];
	$('#tr_price_'+id).remove();
	price_calc();
}
/**
 * 更改计费价格
 * @param string id
 * @param integer price
 * @param string calcMode
 */
function price_change(id, price, calcMode)
{
	__reg_price_list[id].value = price;
	if (calcMode)
	{
		__reg_price_list[id].csingle = calcMode == 'single' ? true : false;
	}
	price_calc();
}
/**
 * 查询表单字段是否存在
 * @param string name
 */
function checkout_field_exists(name)
{
	var fid = 'field_'+name;
	return $('#'+fid).length > 0 ? true : false;
}
/**
 * 添加一个表单字段
 * @param {Object} name
 * @param {Object} value
 */
function checkout_field_append(name, value)
{
	if (checkout_field_exists(name))
	{
		checkout_field_update(name, value);
	}
	else
	{
		var fid = 'field_'+name;
		$('#product_id').after('<input id="'+fid+'" type="hidden" name="'+name+'" value="'+value+'" />');
	}
}
/**
 * 更新表单数据
 * @param string name
 * @param mixed value
 */
function checkout_field_update(name, value)
{
	if (checkout_field_exists(name))
	{
		var fid = 'field_'+name;
		$('#'+fid).val(value);
	}
	else
	{
		checkout_field_append(name, value);
	}
}
/**
 * 删除表单字段
 * @param string name
 */
function checkout_field_remove(name)
{
	if (checkout_field_exists(name))
	{
		var fid = 'field_'+name;
		$('#'+fid).remove();
	}
}
/**
 * 订单提交
 */
function checkout_submit()
{
	$.hook.call('checkout_submit');
	if (if_allow_to_submit())
	{
		var num = parseInt($('#num_buys').val());
		checkout_field_append('num_buys', num);
		$('#checkout_submit').ajaxSubmit({
			beforeSubmit: function()
			{
				$('#checkout_submit_button').attr('disabled', true);
				$('#submit_status').text('正在为您生成订单，请稍候...');
				$('#submit_status').css('display', 'inline');
			},
			success: function(data)
			{
				try {
					eval('var data='+data);
				} catch(e) {
					$('#submit_status').text('服务端错误，请重试！');
					return;
				}
				if (data.status != 'ok')
				{
					$('#submit_status').text(data.msg);
				}
				else
				{
					$('#submit_status').text('已经获取到订单，正在跳转...');
					order_finish(data.id);
				}
				$('#checkout_submit_button').attr('disabled', false);
			}
		});
	}
	return false;
}

function df_allow_to_submit(key, allow)
{
    _allow_to_submit[key] = allow;
}

function if_allow_to_submit()
{
    var _allow = true;
    $.each(_allow_to_submit, function(key, allow){
        if (!allow)
        {
            _allow = false;
        }
    });
    return _allow;
}