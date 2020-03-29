<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/5/21
 * Time: 15:51
 */

class Pagination {

	/**
	 * @param $array
	 * @param $current
	 * @param int $count
	 * @param $url
	 * @return mixed
	 */
	public static function Page($array, $current, $count=15,$url) {
		$current = ($current > 0 ? $current : 1);

		$pagination_info['total'] = count($array);
		$pagination_info['current'] = $current;
		if($current==1){
			$pagination_info['prev'] = 1;
		}else{
			$pagination_info['prev'] = $current - 1;
		}

		$pagination_info['per'] = $count;
		$pagination_info['gap'] = 3;

		$pagination_info['url'] = $url;
		$pagination_info['prefix'] = $pagination_info['prev'];

		$page_count = (int)ceil($pagination_info['total'] / $pagination_info['per']);

		if($page_count==$pagination_info['current']){
			$pagination_info['next'] = $current;
		}else{
			$pagination_info['next'] = $current + 1;
		}

		$pagination_info['count'] = $pagination_info['per'];
		$page_start = (int)$current - $pagination_info['gap'];
		$page_start = ($page_start < 1 ? 1 : $page_start);
		$pagination_info['start'] = $page_start;
		$page_stop = (int)$pagination_info['current'] + $pagination_info['gap'];
		$page_stop = ($page_stop > $page_count ? $page_count : $page_stop);
		$pagination_info['stop'] = $page_stop;

		if($pagination_info['current']==1){
			$content_start = 0;
		}else{
			$content_start = $pagination_info['current']*$pagination_info['per']+1;
		}

		$pagination_info['contents']=array_slice($array,$content_start,$pagination_info['per']);
		return $pagination_info;
	}
}