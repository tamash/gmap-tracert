<?php

	function get_url($url){
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		$content = trim(curl_exec($c));
		curl_close($c);
		return $content;
	}

    $ip = $_SERVER['REMOTE_ADDR'];  //Save the ip address
	$ip_to_search_for = $_GET['ip'];
	$query1 = "http://freegeoip.net/csv/80.99.1.82";//.$ip;
	$query2 = "http://freegeoip.net/csv/".$ip_to_search_for;
	
	$csvtxt1= get_url($query1);
	$llarray1 = explode(",", $csvtxt1);
	
	$csvtxt2= get_url($query2);
	$llarray2 = explode(",", $csvtxt2);

	$start_city = $llarray1[4];
	$end_city = $llarray2[4];
	

	echo $start_city.";".$end_city;
	?>