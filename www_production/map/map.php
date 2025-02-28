<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Heatmaps</title>
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
      #panel {
        position: absolute;
        top: 5px;
        left: 50%;
        margin-left: -180px;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=visualization"></script>
    <script>
var map, pointarray, heatmap;

var taxiData=null; 
// Added by shyam

function loadXMLDoc()
{
//var taxiData; 
var xmlhttp;
 if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
	var resdata=JSON.parse(xmlhttp.responseText);

	var taxiData=new Array();
		
		for(i=0;i<resdata.length;i++)
		{
	 		taxiData.push(new google.maps.LatLng(resdata[i]['lat'],resdata[i]['lon']));
		}
	
	
	/**********   start here ********************/
	var mapOptions = {
    		zoom: 7,
    		center: new google.maps.LatLng(28.666702,77.216705),
    		mapTypeId: google.maps.MapTypeId.TERRAIN
  		};
         map = new google.maps.Map(document.getElementById('map-canvas'),
                mapOptions);
	var pointArray = new google.maps.MVCArray(taxiData);
	heatmap = new google.maps.visualization.HeatmapLayer({
    			data: pointArray
	  });
	heatmap.set('radius',20);
	heatmap.setMap(map);

	/**********    end here *********************/	
    }
}
xmlhttp.open("GET","ajax_get_loc.php",true);
xmlhttp.send();
}  
//

function initialize() {
  var mapOptions = {
    zoom: 8,
    center: new google.maps.LatLng(28.666702,77.216705),
    mapTypeId: google.maps.MapTypeId.TERRAIN
  };

  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);



  var pointArray = new google.maps.MVCArray(taxiData);

  heatmap = new google.maps.visualization.HeatmapLayer({
    data: pointArray
  });

  heatmap.setMap(map);
}

function toggleHeatmap() {
  heatmap.setMap(heatmap.getMap() ? null : map);
}

function changeGradient() {
  var gradient = [
    'rgba(0, 255, 255, 0)',
    'rgba(0, 255, 255, 1)',
    'rgba(0, 191, 255, 1)',
    'rgba(0, 127, 255, 1)',
    'rgba(0, 63, 255, 1)',
    'rgba(0, 0, 255, 1)',
    'rgba(0, 0, 223, 1)',
    'rgba(0, 0, 191, 1)',
    'rgba(0, 0, 159, 1)',
    'rgba(0, 0, 127, 1)',
    'rgba(63, 0, 91, 1)',
    'rgba(127, 0, 63, 1)',
    'rgba(191, 0, 31, 1)',
    'rgba(255, 0, 0, 1)'
  ]
  heatmap.set('gradient', heatmap.get('gradient') ? null : gradient);
}

function changeRadius() {
  heatmap.set('radius', heatmap.get('radius') ? null : 2);
}

function changeOpacity() {
  heatmap.set('opacity', heatmap.get('opacity') ? null :1);
}

google.maps.event.addDomListener(window, 'load', loadXMLDoc);

    </script>
 

 </head>

  <body>
    <div id="panel">
<!--      <button onclick="toggleHeatmap()">Toggle Heatmap</button>
      <button onclick="changeGradient()">Change gradient</button>
      <button onclick="changeRadius()">Change radius</button> -->
      <button onclick="changeOpacity()">Change opacity</button>
     
    </div>
    <div id="map-canvas"></div>
  </body>
</html>
