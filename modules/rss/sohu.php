<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename sohu.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:51 $
 *******************************************************************/ 
 


$wwwurl = $this->Config['site_url'];

$oa = array(
		'Site' => $this->Config['site_name'],
		'SiteUrl' => $wwwurl,
		'Update' => date('Y-m-d'),
		);

$acts = array();

foreach($productList AS $one) {
	$city = $cityList[$one['city']];
	$group = $city;

	$item = array();
	$item['data'] = array();
	$item['data']['display'] = array();

	$o = array();
	$o['Title'] = $one['name'];
	$o['Url'] = "{$wwwurl}/?view={$one['id']}";
	$o['Description'] = $one['intro'];
	$o['ImageUrl'] = imager($one['img']);
	$o['CityName'] = $city;
	$o['AreaCode'] = null;
	$o['Value'] = $one['price'];
	$o['Price'] = $one['nowprice'];
	$o['Rebate'] = $one['discount'];
	$bgtime = str_replace('-', '', $one['begintime']).'000000';
	$ovtime = str_replace('-', '', $one['overtime']).'000000';
	$o['StartTime'] = $bgtime;
	$o['EndTime'] = $ovtime;
	$o['Quantity'] = $one['maxnum'];
	$o['Bought'] = $one['num'];
	$o['MinBought'] = $one['successnum'];
	$o['BoughtLimit'] = $one['oncemax'];

		$g = array();
		$g['Name'] = $one['sellername'];
		$g['ProviderName'] = $one['sellername'];
		$g['ProviderUrl'] = $one['sellerurl'];
		$g['ImageUrlSet'] = imager($one['img']);
		$g['Contact'] = $one['sellerphone'];
		$g['Address'] = $one['selleraddress'];
		$g['Map'] = null;
		$g['Description'] = $one['selleraddress'];

	$o['Goods'] = $g;
	$oa[] = $o;
}

header('Content-Type: application/xml; charset=UTF-8');
Output::SetTagSon('ActivitySet', 'Activity');
if (ENC_IS_GBK) $oa = array_iconv('GBK', 'UTF-8', $oa);
Output::XmlCustom($oa, 'ActivitySet');
?>