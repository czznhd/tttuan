<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename jutao.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:51 $
 *******************************************************************/ 
 

 
 

$si = array
(
	'site_name' => $this->Config['site_name'],
	'site_title' => $this->Config['site_name'],
	'site_url' => $this->Config['site_url']
);
$oa = array();
foreach($productList AS $one) {
	$city = $cityList[$one['city']];
	$group = $city;
	$item = array();
	$item['loc'] = "{$si['site_url']}/?view={$one['id']}";
	$item['data'] = array();
	$item['data']['display'] = array();
	$item['data']['componays']['componay']= array();

	$o = array();
	$o['website'] = $si['site_name'];
	$o['siteurl'] = $si['site_url'];
	$o['city'] = $city;
	$o['title'] = $one['name'];
	$o['image'] = imager($one['img']);
	$o['soldout'] = ($one['surplus'] <= 0) ? 'yes' : 'no';
	$o['buyer'] = $one['succ_buyers'];
	$o['start_date'] = date('Y-m-d H:i:s', $one['begintime']);
	$o['end_date'] = date('Y-m-d H:i:s', $one['overtime']);
	$o['expire_date'] = 0;
	$o['oriprice'] = $one['price'];
	$o['curprice'] = $one['nowprice'];
	$o['discount'] = $one['discount'];
	$o['tip'] = $one['intro'];
	$o['detail'] = $one['content'];
	$item['data']['display'] = $o;
	$pval = array();
	$pval['name'] = $one['sellername'];
	$pval['contact'] = $one['sellerphone'];
	$pval['address'] = $one['selleraddress'];
	$item['data']['componays']['componay']=$pval;
	$oa[] = $item;
}

header('Content-Type: application/xml; charset=UTF-8');
if (ENC_IS_GBK) $oa = array_iconv('GBK', 'UTF-8', $oa);
Output::XmlBaidu($oa);
?>