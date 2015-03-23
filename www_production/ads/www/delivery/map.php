<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<?php 
require_once '../../lib/ELogger.php';
global $log;
$log = new ELogger ( "/var/www/log" , ELogger::DEBUG );
$log->logInfo("Entered map.php");
?>
<html> 
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=7; IE=EmulateIE9; IE=10" />
		
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
		
		<meta name="description" content="Routing Manager offers the ability to request a route with various modes between two points"/>
		<meta name="keywords" content="routing, services, a to b, route, direction, navigation"/>
		<!-- For scaling content for mobile devices, setting the viewport to the width of the device-->
		<meta name=viewport content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
		
		<!-- By default we add ?with=all to load every package available, it's better to change this parameter to your use case. Options ?with=maps|positioning|places|placesdata|directions|datarendering|all -->
		<script type="text/javascript" charset="UTF-8" src="http://js.cit.api.here.com/se/2.5.3/jsl.js?with=all"></script>
		
		<style type="text/css">
			html {
				overflow:hidden;
			}
			
			body {
				margin: 0;
				padding: 0;
				overflow: hidden;
				width: 100%;
				height: 100%;
				position: absolute;
			}
			
			#mapContainer {
				width: 100%;
				height: 100%;
				left: 0;
				top: 0;
				position: absolute;
			}
		</style>
	</head>
	<body>
		<div id="mapContainer"></div>
		
<script type="text/javascript" id="exampleJsSource">

nokia.Settings.set("app_id", "mMQ9oFOwsq4rdjY4Vmg5"); 
nokia.Settings.set("app_code", "TcxZ9XaYhZvAmrKSUUCn7g");
// Use staging environment (remove the line for production environment)
nokia.Settings.set("serviceMode", "cit");

// Get the DOM node to which we will append the map
var mapContainer = document.getElementById("mapContainer");

	var posLatitude;
	var posLongitude ;
	var curLatitude;
	var curLongitude;

	
	function get(name)
    {
   		if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search))
      	return decodeURIComponent(name[1]);
	}

	posLatitude 	=	Number(get("maplat")); 
	posLongitude 	=	Number(get("maplon")); 
	curLatitude 	=	Number(get("curlat")); 
	curLongitude 	=	Number(get("curlon")); 
	
	// Create a map inside the map container DOM node
	
	var map = new nokia.maps.map.Display(mapContainer, {
		// Initial center and zoom level of the map
		center: [curLatitude, curLongitude],
		zoomLevel: 18,
		// We add the behavior component to allow panning / zooming of the map
		components:[new nokia.maps.map.component.Behavior()]
	}),
	router = new nokia.maps.routing.Manager(); // create a route manager;
	
// The function onRouteCalculated  will be called when a route was calculated
var onRouteCalculated = function (observedRouter, key, value) {
		if (value == "finished") {
			var routes = observedRouter.getRoutes();
			
			//create the default map representation of a route
			var mapRoute = new nokia.maps.routing.component.RouteResultSet(routes[0]).container;
			map.objects.add(mapRoute);
			
			//Zoom to the bounding box of the route
			map.zoomTo(mapRoute.getBoundingBox(), false, "default");
		} else if (value == "failed") {
			alert("The routing request failed.");
		}
	};
	
/* We create on observer on router's "state" property so the above created
 * onRouteCalculated we be called once the route is calculated
 */
router.addObserver("state", onRouteCalculated);

// Create waypoints
var waypoints = new nokia.maps.routing.WaypointParameterList();
waypoints.addCoordinate(new nokia.maps.geo.Coordinate(curLatitude, curLongitude ));
waypoints.addCoordinate(new nokia.maps.geo.Coordinate(posLatitude, posLongitude  ));

/* Properties such as type, transportModes, options, trafficMode can be
 * specified as second parameter in performing the routing request.
 * 
 * See for the mode options the "nokia.maps.routing.Mode" section in the developer's guide
 */
var modes = [{
	type: "shortest", 
	transportModes: ["car"],
	options: "avoidTollroad",
	trafficMode: "default"
}];

// Trigger route calculation after the map emmits the "displayready" event
map.addListener("displayready", function () {
	router.calculateRoute(waypoints, modes);
}, false);


		</script>
	</body>
</html>

