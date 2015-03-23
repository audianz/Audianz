<table id="inlinetable">
	<tr>
		<td><textarea cols="120" rows="20" readonly="yes" name="textarea"
				class="textalign" onMouseOver="selectall(this);"
				onmousedown="selectall(this);">
				<?php if($comments =="1") { 
					echo "<!-- /* OpenX Image Tag v2.8.7 "; ?>
				<?php if($party =="doubleclick") { 
					echo " (Rich Media - Doubleclick) ";
				} else if($party =="openx") {
echo " (Rich Media - OpenX) ";
} ?>
				<?php echo " */ -->"; ?>

<?php echo "<!-- /*
		* The backup image section of this tag has been generated for use on a
		* non-SSL page. If this tag is to be placed on an SSL page, change the
		*   'http://localhost/product/ads/www/delivery/...'
		*  to
		*   'https://localhost/product/ads/www/delivery/...'
		*
		* Put all the <script></script> tags inside <head></head> section of your
		* page.
		* Put the <div> tag where you want image banner to be displayed on your page
		*/ -->";
		}
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="<?php echo base_url();?>jq.js"></script>
<script type="text/javascript" charset="UTF-8" src="http://api.maps.nlp.nokia.com/2.2.3/jsl.js"></script>
<script>function display(lat,lon)
{	var ad="<?php echo base_url();?>ads/www/delivery/oxm_avw.php?zoneid=<?php echo $zoneid; ?><?php if($refresh) { echo "&amp;amp;refresh=".$refresh; } else{ echo "&amp;amp;refresh=90"; } ?><?php if($source) { echo "&amp;amp;source=".$source_sel; } if($cachebuster) { echo "&amp;amp;cb=INSERT_RANDOM_NUMBER_HERE"; } ?><?php echo "&amp;amp;gender=&amp;amp;age=&amp;amp;teleco="; ?><?php if($party =="generic") { echo "&amp;amp;ct0=INSERT_CLICKURL_HERE"; } if($party =="doubleclick") { echo "&amp;amp;ct0=%c";} if($party=="openx") { echo "&amp;amp;ct0={clickurl}"; } ?>&lat="+lat+"&lon="+lon+"";
	$.ajax({url: ad,type: 'GET',dataType: 'script',
	    success: function(res) { var responseData = res.responseText;var n=responseData.split("<body>");var ifrm = n[1].split("</body>");document.getElementById("imageDiv").innerHTML=ifrm[0];
}});}
</script>
<script type="text/javascript" id="exampleJsSource">
if (navigator.geolocation) {navigator.geolocation.getCurrentPosition(function (position){display(position.coords.latitude,position.coords.longitude);},function (error){display(0,0);});}else{display(0,0);}</script>
<div id="imageDiv"></div>
</textarea></td></tr>
</table>
