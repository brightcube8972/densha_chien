<?php
require_once ('densha_chien_const_area_tetsudokaisha.php');
require_once ('densha_chien_const_common.php');

$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";
$category = isset($_REQUEST["category"]) ? $_REQUEST["category"] : "";
$area = isset($_REQUEST["area"]) ? $_REQUEST["area"] : "";

if ($mode == "json_get") {
	$densha_chien_list = array();

	$rss = simplexml_load_file(rssURL);
	
	$area = '【' . $area . '】';
	
	foreach ($rss->item as $item) {
		$x = array();
		//titleを分解して会社名と沿線名を取得
		$title_array = explode("】", (string)$item -> title);
		$kaisha_name = str_replace("【", "", $title_array[0]);
		$x['kaisha_name'] = $kaisha_name;
		$x['ensen_name'] = $title_array[1];
		//$kaisha_nameからエリアを取得
		$x['area'] = getArea($kaisha_name);
		//descriptionから丸カッコ内の時間を取得
		preg_match('/^.*\((.*)\).*$/', (string)$item -> description, $match_array);
		$today = date("Y/n/j");
		$time = str_replace("現在", "", $match_array[1]);
		$x['time'] = $today.' '.$time;
		//なぜか空文字をつけないと正常にlinkが取得できないので''は前付けする。
		$x['link'] = ''.(string)$item -> link;	
		
		//$areaが全国以外ならソート
		if($area != '【全国】'){
			if($area == $x['area'] ){
				$densha_chien_list[] = $x;
			}
		}else{
			$densha_chien_list[] = $x;	
		}
	}


	if($category == 'SaishinJouhou'){
		$data = array(
			"Status" => "OK", 
			"densha_chien_list" => $densha_chien_list, 
			"totalItems" => count($densha_chien_list),
			"category_SaishinJouhouItem" => $category_SaishinJouhouItem, 
			"category_TetsudoKakushaIchiranItem" => $category_TetsudoKakushaIchiranItem, 
			"AppStoreMyURL" => AppStoreMyURL,
			"info_app_update" => $info_app_update,
			"push_notice_URL" => $push_notice_URL);
	}else if($category == 'TetsudoKakushaIchiran'){
		
		$tetsudo_kaisha_list = getTetsudoKaishaList($area,$densha_chien_list);
		
		$data = array(
			"Status" => "OK", 
			"densha_chien_list" => $densha_chien_list, 
			"tetsudo_kaisha_list" => $tetsudo_kaisha_list,
			"category_SaishinJouhouItem" => $category_SaishinJouhouItem, 
			"category_TetsudoKakushaIchiranItem" => $category_TetsudoKakushaIchiranItem, 
			"AppStoreMyURL" => AppStoreMyURL,
			"push_notice_URL" => $push_notice_URL);
	}
	
	header('Content-type: application/json');
	echo json_encode($data);
	exit();

} else {
	error_response("101");
}

function getArea($kaisha_name) {
	extract($GLOBALS);

	$ret_area = '';
	foreach ($shinkansen_kaisha_name as $key => $value) {
		if ($kaisha_name == $value) {
			$ret_area = '新幹線';
		}
	}
	foreach ($kantou_kaisha_name as $key => $value) {
		if ($kaisha_name == $value) {
			$ret_area = '関東';
		}
	}
	foreach ($toukai_kaisha_name as $key => $value) {
		if ($kaisha_name == $value) {
			$ret_area = '東海';
		}
	}
	foreach ($kinki_kaisha_name as $key => $value) {
		if ($kaisha_name == $value) {
			$ret_area = '近畿';
		}
	}
	foreach ($kyushu_kaisha_name as $key => $value) {
		if ($kaisha_name == $value) {
			$ret_area = '九州';
		}
	}
	foreach ($hokkaidou_kaisha_name as $key => $value) {
		if ($kaisha_name == $value) {
			$ret_area = '北海道';
		}
	}
	foreach ($touhoku_kaisha_name as $key => $value) {
		if ($kaisha_name == $value) {
			$ret_area = '東北';
		}
	}
	foreach ($hokushinetsu_kaisha_name as $key => $value) {
		if ($kaisha_name == $value) {
			$ret_area = '北信越';
		}
	}
	foreach ($chuugoku_kaisha_name as $key => $value) {
		if ($kaisha_name == $value) {
			$ret_area = '中国';
		}
	}
	foreach ($shikoku_kaisha_name as $key => $value) {
		if ($kaisha_name == $value) {
			$ret_area = '四国';
		}
	}
	foreach ($okinawa_kaisha_name as $key => $value) {
		if ($kaisha_name == $value) {
			$ret_area = '沖縄';
		}
	}
	if ($ret_area == '') {
		$ret_area = 'その他';
	}

	$ret_area = '【' . $ret_area . '】';

	return $ret_area;
}

function getTetsudoKaishaList($func_area,$densha_chien_list) {
	
	extract($GLOBALS);
	
	$func_area = str_replace("【", "", $func_area);
	$func_area = str_replace("】", "", $func_area);
	
	$ret_array = array();
	
	if($func_area == '新幹線'){
		$ret_array = getTetsudoKaishaList2Area($densha_chien_list,$shinkansen_kaisha_name,$shinkansen_time_detail,$shinkansen_url);
	}else if($func_area == '北海道'){
		$ret_array = getTetsudoKaishaList2Area($densha_chien_list,$hokkaidou_kaisha_name,$hokkaidou_time_detail,$hokkaidou_url);
	}else if($func_area == '東北'){
		$ret_array = getTetsudoKaishaList2Area($densha_chien_list,$touhoku_kaisha_name,$touhoku_time_detail,$touhoku_url);
	}else if($func_area == '関東'){
		$ret_array = getTetsudoKaishaList2Area($densha_chien_list,$kantou_kaisha_name,$kantou_time_detail,$kantou_url);
	}else if($func_area == '東海'){
		$ret_array = getTetsudoKaishaList2Area($densha_chien_list,$toukai_kaisha_name,$toukai_time_detail,$toukai_url);
	}else if($func_area == '北信越'){
		$ret_array = getTetsudoKaishaList2Area($densha_chien_list,$hokushinetsu_kaisha_name,$hokushinetsu_time_detail,$hokushinetsu_url);
	}else if($func_area == '近畿'){
		$ret_array = getTetsudoKaishaList2Area($densha_chien_list,$kinki_kaisha_name,$kinki_time_detail,$kinki_url);
	}else if($func_area == '中国'){
		$ret_array = getTetsudoKaishaList2Area($densha_chien_list,$chuugoku_kaisha_name,$chuugoku_time_detail,$chuugoku_url);
	}else if($func_area == '四国'){
		$ret_array = getTetsudoKaishaList2Area($densha_chien_list,$shikoku_kaisha_name,$shikoku_time_detail,$shikoku_url);
	}else if($func_area == '九州'){
		$ret_array = getTetsudoKaishaList2Area($densha_chien_list,$kyushu_kaisha_name,$kyushu_time_detail,$kyushu_url);
	}else if($func_area == '沖縄'){
		$ret_array = getTetsudoKaishaList2Area($densha_chien_list,$okinawa_kaisha_name,$okinawa_time_detail,$okinawa_url);
	}else if($func_area == 'その他'){
		$ret_array = getTetsudoKaishaList2Area($densha_chien_list,$sonota_kaisha_name,$sonota_time_detail,$sonota_url);
	}
	return $ret_array;
}

function getTetsudoKaishaList2Area($densha_chien_list,$array_kaisha_name,$array_time_detail,$array_url) {
	$ret_array = array();
	$ret_array['kaisha_name'] = $array_kaisha_name;
		if(!empty($densha_chien_list)){			
			for ($i=0; $i<count($array_kaisha_name); $i++) {
				for ($j=0; $j<count($densha_chien_list); $j++) {
					if($ret_array['kaisha_name'][$i] == $densha_chien_list[$j]['kaisha_name']){
						$ret_array['kaisha_ensen'][$i] = $densha_chien_list[$j]['ensen_name'];
						$ret_array['kaisha_detail'][$i] = $densha_chien_list[$j]['time'];
						$ret_array['kaisha_url'][$i] = $densha_chien_list[$j]['link'];
				 		$ret_array['status_filename'][$i] = 'status_label_ari.png';
					}else{
						if(empty($ret_array['kaisha_ensen'][$i])){
							$ret_array['kaisha_ensen'][$i] = '';
							$ret_array['kaisha_detail'][$i] = $array_time_detail[$i];
							$ret_array['kaisha_url'][$i] = $array_url[$i];
				 			$ret_array['status_filename'][$i] = 'status_label_nashi.png';	
						}
					}
				}
			}
		}else{
			for ($i=0; $i<count($array_kaisha_name); $i++) {
				 $kaisha_ensen[] = '';
				 $status_filename[] = 'status_label_nashi.png';
			}
			$ret_array['kaisha_ensen'] = $kaisha_ensen;
			$ret_array['kaisha_detail'] = $array_time_detail;
			$ret_array['kaisha_url'] = $array_url;
			$ret_array['status_filename'] = $status_filename;
		}
		
		return $ret_array;
}

//エラーレスポンス
function error_response($errorno = "") {
	$data = array("Status" => "NG", "Error" => $errorno, );
	echo json_encode($data);
	exit();
}
?>