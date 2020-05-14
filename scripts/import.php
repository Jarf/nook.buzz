<?php

$importdir = dirname(__FILE__) . '/../../villagerdb/data/';

$filelist = scandir($importdir . 'items/');

$categories = array();

foreach($filelist as $file){
	$data = file_get_contents($importdir . 'items/' . $file);
	// Check if new horizons
	if(strpos($data, '"nh":')){
		$data = json_decode($data, true);
		$categories[] = $data['category'];
	}
}

$categories = array_unique($categories);


?>