<?php

function get_url($url){
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		$content = trim(curl_exec($c));
		curl_close($c);
		return $content;
}


$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

header("Content-type: text/xml");


	
$ips = array();
$descriptions = array();
//$_ip = $_SERVER['REMOTE_ADDR'];
$_ip = $_GET['yourip'];
//echo "<b>Enter the IP or the domain name of the server that you are trying to route.</b><br>";
//echo "<form method='post' action='?do=route'><input type='text' name='domain' class='input_login' value='$_ip'>&nbsp;".
	 "<input type='submit' value='Route' class='input_login'></form>";
	 
if($_GET['do'] == 'route')
{
$_domain = $_GET['domain'];
				    exec ("tracert $_domain", $line);
				    foreach ($line as $k=>$v){
						if($k>3 && $v!=""){
							$domain = strstr($v, '[');
							$tags = array("[", "]");
							$domain = str_replace($tags,"",$domain);
							$first_part = explode("ms", $v);
							//$second_part = explode(" ", $first_part[3]);
							$second_part = substr($first_part[3], 2);
							//echo $second_part."<br />";
							array_push($ips, $domain);
							array_push($descriptions, $second_part);
						}
					}
}

$ips_length = count($ips);
unset($ips[$ips_length-1]);
unset($descriptions[count($descriptions)-1]);

foreach($ips as $k=>$value){
		$csvtxt= get_url("http://freegeoip.net/csv/".$value);
		$llarray = explode(",", $csvtxt);
	
		$lat = floatval($llarray[7]);
		$lon = floatval($llarray[8]);
		$city = $llarray[4];
		$country = $llarray[2];
		//echo $lat.",".$lon."<br />";
		
		$node = $dom->createElement("marker");
			$newnode = $parnode->appendChild($node);
			$newnode->setAttribute("id", $k);
			$newnode->setAttribute("description", $descriptions[$k]);
			$newnode->setAttribute("city", $city);
			$newnode->setAttribute("country", $country);
			$newnode->setAttribute("lat", $lat);
			$newnode->setAttribute("lng", $lon);
}
echo $dom->saveXML();
 ?>