<?php
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package php
 * @name isearcher.php
 * @date 2013-07-31 16:18:09
 */
 


$config['isearcher'] = array(
'idx' => array(
	'admin' => array(
		'product_list' => 'product_name,seller_name,city_name',
		'order_list' => 'product_name,order_id,user_name',
		'coupon_list' => 'product_name,order_id,user_name,coupon_no',
		'delivery_list' => 'product_name,order_id,user_name',
        'recharge_card_list' => 'recharge_card_number',
        'recharge_order_list' => 'recharge_order_id,recharge_user_name'
	)
),
'frc' => array(
	'admin' => array(
		'order_list' => 'order_status,order_process',
		'coupon_list' => 'coupon_status',
        'recharge_card_list' => 'recharge_card_usetime',
        'product_list' => 'product_status,product_dsp_area',
        'recharge_order_list' => 'recharge_order_status'
	)
),
'tvs' => array(
	'admin' => array(
		'order_list' => 'order_main',
		'recharge_order_list' => 'recharge_order_main'
	)
),
'map' => array(
	'product_name' => array(
        'name' => '产品名称',
        'table' => 'product',
        'src' => 'flag',
        'key' => 'pid',
        'idx' => 'id'
    ),
	'seller_name' => array(
        'name' => '商家名称',
        'table' => 'seller',
        'src' => 'sellername',
        'key' => 'sid',
        'idx' => 'id'
    ),
	'city_name' => array(
        'name' => '城市名称',
        'table' => 'city',
        'src' => 'cityname',
        'key' => 'cid',
        'idx' => 'cityid'
    ),
	'order_id' => array(
		'name' => '订单编号',
		'table' => 'order',
		'src' => 'orderid',
		'key' => 'oid',
		'idx' => 'orderid'
	),
	'user_name' => array(
		'name' => '用户名',
		'table' => 'members',
		'src' => 'username',
		'key' => 'uid',
		'idx' => 'uid'
	),
	'coupon_no' => array(
		'name' => '团购券号码',
		'table' => 'ticket',
		'src' => 'number',
		'key' => 'coid',
		'idx' => 'ticketid'
 	),
    'recharge_card_number' => array(
        'name' => '充值卡号码',
        'table' => 'recharge_card',
        'src' => 'number',
        'key' => 'rcid',
        'idx' => 'id'
     ),
    'recharge_order_id' => array(
		'name' => '订单编号',
		'table' => 'recharge_order',
		'src' => 'orderid',
		'key' => 'orderid',
		'idx' => 'orderid'
	),
	'recharge_user_name' => array(
		'name' => '用户名',
		'table' => 'members',
		'src' => 'username',
		'key' => 'userid',
		'idx' => 'uid'
	)
),
'filter' => array(
	'order_status' => array(
		'name' => '订单状态',
		'key' => 'ordsta',
		'list' => array(
			ORD_STA_Normal => '订单正常',
			ORD_STA_Cancel => '已经取消',
            ORD_STA_Failed => '订单失败',
            ORD_STA_Overdue => '已经过期',
            ORD_STA_Refund => '已经返款'
		)
	),
	'order_process' => array(
		'name' => '处理进程',
		'key' => 'ordproc',
		'list' => array(
            '__CREATE__' => '创建订单',
            '__PAY_YET__' => '已经付款',
            'WAIT_BUYER_PAY' => '等待付款',
            'WAIT_SELLER_SEND_GOODS' => '等待发货',
            'WAIT_BUYER_CONFIRM_GOODS' => '等待收货',
            'TRADE_FINISHED' => '交易完成'
		)
	),
	'coupon_status' => array(
		'name' => '团购券状态',
		'key' => 'coupsta',
		'list' => array(
			TICK_STA_Unused => '还未使用',
            TICK_STA_Used => '已经使用',
            TICK_STA_Overdue => '已经过期',
            TICK_STA_Invalid => '号码无效'
		)
	),
    'recharge_card_usetime' => array(
        'name' => '使用状态',
        'key' => 'used',
        'list' => array(
            0 => '还未使用',
            1 => '已经使用'
        )
    ),
    'product_status' => array(
        'name' => '产品状态',
        'key' => 'prosta',
        'list' => array(
            PRO_STA_Success => '进行中，已成团',
            PRO_STA_Normal => '进行中，未成团',
            PRO_STA_Finish => '已结束，团购成功',
            PRO_STA_Failed => '已结束，团购失败',
            PRO_STA_Refund => '已结束，已经返款'
        )
    ),
    'product_dsp_area' => array(
        'name' => '显示区域',
        'key' => 'prodsp',
        'list' => array(
            PRO_DSP_Global => '全部城市显示',
            PRO_DSP_City => '限定城市显示',
            PRO_DSP_None => '不在前台显示'
        )
    ),
    'recharge_order_status' => array(
        'name' => '支付状态',
        'key' => 'paystatus',
        'list' => array(
            0 => '还未支付',
            1 => '已经支付'
        )
    )
),
'timev' => array(
	'order_main' => array(
		array(
			'name' => '下单时间',
			'field' => 'buytime',
			'key' => 'ordbt'
		),
		array(
			'name' => '付款时间',
			'field' => 'paytime',
			'key' => 'ordpt'
		)
	),
	'recharge_order_main' => array(
		array(
			'name' => '支付时间',
			'field' => 'paytime',
			'key' => 'paytime'
		)
	)
)
);

?>