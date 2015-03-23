<table id="inlinetable">
  <tr>
    <td><textarea cols="120" rows="20" readonly="yes" name="textarea" class="textalign" onMouseOver="selectall(this);" onmousedown="selectall(this);"><?php if($comments =="1") { echo "<!-- /* OpenX Textad Javascript Tag v2.8.7 "; if($party =="doubleclick") { echo " (Rich Media - Doubleclick) "; } else if($party=="openx") { echo " (Rich Media - OpenX) "; } echo "*/ -->"; } ?>	
		
<script type="text/javascript" charset="UTF-8" src="http://api.maps.nlp.nokia.com/2.2.3/jsl.js"></script>
<script language="javascript" type="text/javascript">
function display(lat,lon)
{document.getElementById('textDiv').innerHTML='<iframe src="<?php echo base_url();?>ads/www/delivery/oxm_ajs.php?zoneid=<?php echo $zoneid;?><?php if($target) { echo "&amp;amp;target=".$target; } ?><?php if($refresh){ echo "&amp;amp;refresh= ".$refresh; } else{ echo "&amp;amp;refresh=90"; } ?><?php if($source) { echo "&amp;amp;source=".$source_sel; } ?><?php if(($party !='doubleclick') && $party !='openx') { echo "&amp;amp;cb=INSERT_RANDOM_NUMBER_HERE"; } ?><?php if($party =="doubleclick"){ echo "&amp;amp;cb=%n"; echo "&amp;amp;ct0=%c"; } if($party =="generic") { echo "&amp;amp;ct0=INSERT_CLICKURL_HERE"; } if($party =="openx") { echo "&amp;amp;cb={random}"; echo "&amp;amp;ct0={clickurl}"; } ?>&amp;lat='+lat+'&amp;lon='+lon+'" frameborder="0" scrolling="no"/>'; }
</script>
<script type="text/javascript" id="exampleJsSource">
if (navigator.geolocation){navigator.geolocation.getCurrentPosition(function (position){display
(position.coords.latitude,position.coords.longitude);},function (error){display(0,0);});}else{display(0,0);}</script>
<div id="textDiv"></div>
</textarea>
    </td>
  </tr>
</table>
