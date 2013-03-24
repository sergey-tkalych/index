<?php
	$configFile = $_SERVER['DOCUMENT_ROOT'] . 'config.json';
	$config = json_decode(file_get_contents($configFile), true);
	$href = $_GET['href'];
	$isSet = true;

	if(in_array($href, $config['favorite'])){
		$tempFavorite = array();
		foreach($config['favorite'] as $i => $content){
			if($content !== $href){
				$tempFavorite[] = $content;
			}else{
				$contentIndex = $i;
			}
		}
		$config['favorite'] = $tempFavorite;
		$isSet = false;
	}else{
		array_unshift($config['favorite'], $href);
		$contentIndex = count($config['favorite']) - 1;
	}
	file_put_contents($configFile, json_encode($config));

	echo json_encode(array(
		"isSet" => $isSet,
		"contentIndex" => $contentIndex,
		"favorite" => $config['favorite']
	));