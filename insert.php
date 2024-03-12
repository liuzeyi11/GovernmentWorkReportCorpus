<?php
	header("content-type:text/html; charset=utf-8");
	$conn = mysqli_connect("localhost","root","");
	if (!$conn)
		die('Could not connect: ' . mysql_error());

	mysqli_select_db($conn, "gr_co");
	$tmx_sql = "
	CREATE TABLE IF NOT EXISTS GR2021
	(
	id INT UNSIGNED AUTO_INCREMENT,
	zh_CN TEXT NOT NULL,
	en_US TEXT NOT NULL,
    PRIMARY KEY (id)
	)
	ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;
	";
	mysqli_query($conn, $tmx_sql);

	$xml = simplexml_load_file("GR2021.tmx");
	$json = json_encode($xml);
	$jsondata = json_decode($json,true);

	mysqli_select_db($conn,"gr_co");
	mysqli_query($conn,"set names utf8");
	foreach ($jsondata["body"]["tu"] as $tu)
	{
		$zh_CN = mysqli_real_escape_string($conn, $tu["tuv"][0]["seg"]);
		$en_US = mysqli_real_escape_string($conn, $tu["tuv"][1]["seg"]);
		$insert_sql = "insert into GR2021 (zh_CN, en_US)
		values('$zh_CN', '$en_US')";
		$result = mysqli_query($conn, $insert_sql);
	}

	mysqli_close($conn);
?>