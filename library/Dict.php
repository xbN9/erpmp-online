<?php 

class Dict {

	public static $dict_array = array(
		'pingtai' => array(
			0 => 'PC',
			1 => 'WAP',
			10 => 'iOS',
			11 => 'Android',
		),
	);

	public static $order_pt = array(
		'pingtai' => array(
			1 => 'PC',
			3 => 'iOS',
			4 => 'Android',
			5 => '口袋购物',
			6 => '新浪微卖',
			7 => '顺丰嘿客',
			8 => 'Wap',
			9 => '空中商城',
			10 => '崩豆亲子',
			11 => '微店',
			100 => '妈米分销',
			101 => '红黄蓝',
		),
	);

	public static $extra = array(1,2,11,19,20);

	public static function trans($dict,$value,$keyId = 0){
		//因为user表的平台同orders表的平台不一致，做特殊处理
		if(!in_array($keyId,self::$extra)){
			$result = isset(self::$order_pt[$dict][$value]) ? self::$order_pt[$dict][$value] : $value;
		}else{
			$result = isset(self::$dict_array[$dict][$value]) ? self::$dict_array[$dict][$value] : $value;
		}
		return $result;
	}

	
	
}