<?php

	header("content-type:text/html; charset=utf-8"); 

	$xml = simplexml_load_file('19.tmx');
	$json = json_encode($xml);
	$jsondata = json_decode($json,true);

	foreach ($jsondata["body"]["tu"] as $tu)
	{
		echo $tu["tuv"][0]["seg"]."<br>";
		echo $tu["tuv"][1]["seg"]."<br>";
		echo "<br>";
	}

?>