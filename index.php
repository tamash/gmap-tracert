<?php
//$_ip = $_SERVER['REMOTE_ADDR'];
$_ip = "80.99.1.82";
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Electrolize">
<style type="text/css">
html{font-family:Electrolize;}
input{font-family:Electrolize;}

.wait_screen_dialog{
	position:absolute;
	z-index:999;
	top:200px;
	left:170px;
	font-family:Electrolize;
	border:1px solid black;
	width:250px;
	height:110px;
	background-color:grey;
	-moz-border-radius: 5px;
	-webkit-border-radius:5px;
	-khtml-border-radius:5px;
	border-radius:5px;
	visibility:hidden;
}

.wait_screen{
	position:absolute;
	width:600px;
	height:550px;
	background-color:black;
	opacity:0.4;
	filter:alpha(opacity=40);
	visibility:hidden;
}

.map_canvas{
	position:absolute;
	width:600px; 
	height:550px;
}

.wrapper{
	position:relative;
	width:1010px;
	height:600px;
	clear:both;
}
</style>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="js/util.js"></script>
<script type="text/javascript">
  var map;
  function initialize() {
    var latlng = new google.maps.LatLng(51,0);
    var myOptions = {
      zoom: 2,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map_canvas"),
        myOptions);
  }
 
  function wait(arg){
	if(arg=="on"){
		document.getElementById('wait_screen').style.visibility = 'visible';
		document.getElementById('wait_screen_dialog').style.visibility = 'visible';
	}else if(arg=="off"){
		document.getElementById('wait_screen').style.visibility = 'hidden';
		document.getElementById('wait_screen_dialog').style.visibility = 'hidden';
	}
  }   
  
  
  function traceRT(arg){
	wait('on');
	
	var domainip = arg;
	var pathCoords = [];
	
	var bounds = new google.maps.LatLngBounds();
	
	document.getElementById('start_location').innerHTML = '<?php echo $_ip;?>';
	document.getElementById('end_location').innerHTML = arg;
	
	downloadUrl("traceroute.php?do=route&yourip=<?php echo $_ip;?>&domain="+domainip, function(data, status) {
		if(status=="200"){
			wait('off');
		}
		var markers = data.documentElement.getElementsByTagName("marker");
		var hops = "";
			for(i=0;i<markers.length;i++){
				var id = parseFloat(markers[i].getAttribute("id"));
				var description = markers[i].getAttribute("description");
				var lat = parseFloat(markers[i].getAttribute("lat"));
				var lng = parseFloat(markers[i].getAttribute("lng"));
				var latlng = new google.maps.LatLng(lat, lng);
				bounds.extend(latlng);
				pathCoords.push(latlng);
				var image = "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld="+(id+1)+"|FF0000|000000";
				var marker = new google.maps.Marker({position:latlng, map: map, icon: image, draggable: true});
				hops += "<li>"+description+"</li>";
			}
		document.getElementById('intmhops').innerHTML = hops;
		
		var tracepath = new google.maps.Polyline({
			path: pathCoords,
			strokeColor: "#FF0000",
			strokeOpacity: 1.0,
			strokeWeight: 2
		});

		tracepath.setMap(map);	
		map.fitBounds(bounds);		
	});
  }
</script>
</head>
<body onload="initialize()">
<p>Enter the ip address you want to trace route to:
<input type="text" id="iptotrace"/>
<input type="button" id="starttrace" value="Start tracing" onclick="if(document.getElementById('iptotrace').value!=''){traceRT(document.getElementById('iptotrace').value)}else{return;}"/>	
  <div id="wrapper" class="wrapper">
	  <div id="map_canvas" class="map_canvas"></div>
	  <div id="wait_screen" class="wait_screen"></div>
	  <div id="wait_screen_dialog" class="wait_screen_dialog">
		<center>
			<p>
			<p>Looking up IP locations and performing traceroute
			<p>
			<img src="images/loader.gif"/>
		</center>
	  </div>
  <div style="float:right;position:relative;width:400px;height:550px;">
  <p>Start location:
  <span id="start_location">Budapest [80.99.7.254]</span>
  <p>End location:
  <span id="end_location">Moscow [80.99.7.254]</span>
  <hr>
  <div style="width:100%;height:462px;overflow:auto">
  <p>Intermediate hops:
  <ol id="intmhops">
  </ol>
  </div>
  </div>  
  </div>

</body>
</html>