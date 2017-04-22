<?php

	$filePath = getcwd() . '/sitemap.xml';
	if(!file_exists($filePath)){
		echo 'File doesnt exist';
		exit();
	}
		
	$reader = new XMLReader();

	$reader->open($filePath);
	$i = 0;

	while($reader->read()){
		if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'url') {
			$node = new \SimpleXMLElement($reader->readOuterXML());
			$url = (string) $node->loc; 
			$ch = curl_init($url); 
			curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
			curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_TIMEOUT,10);
			$output = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
	
			if($httpcode != 200){
				echo 'INVALID HTTP CODE [' . $httpcode . '] ' . $url . "\n";
			}
			$i++;			
		}
	}

	echo 'Done! Total ' . $i . " urls checked \n";


