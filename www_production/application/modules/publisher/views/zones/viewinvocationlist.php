<form id="form" action="" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $this->lang->line("label_inventory_zone_invocation_title");?> </legend>
                    
					<p id="sec1">
						<textarea  rows="23" cols="140">
						<!--/* OpenX Textad-Javascript Tag v2.8.7*/--><script type='text/javascript'><!--//<![CDATA[
						 var m3_u = (location.protocol=='https:'?'http://50.23.160.154/~mobileui/MobileUI/openx-server/www/delivery/ajs.php':'http://50.23.160.154/~mobileui/MobileUI/openx-server/www/delivery/ajs.php');
						   var m3_r = Math.floor(Math.random()*99999999999);
						   if (!document.MAX_used) document.MAX_used = ',';
						   document.write ("<scr"+"ipt type='text/javascript' src='"+m3_u);
						   document.write ("?zoneid=129");
						   document.write ('&amp;cb=' + m3_r);
						   document.write ("&amp;gender=");
						   document.write ("&amp;age=");
						   
						   if (document.MAX_used != ',') document.write ("&amp;exclude=" + document.MAX_used);
							  document.write (document.charset ? '&amp;charset='+document.charset : (document.characterSet ? '&amp;charset='+document.characterSet : ''));
							  document.write ("&amp;loc=" + escape(window.location));
						   if (document.referrer) document.write ("&amp;referer=" + escape(document.referrer));
						   if (document.context) document.write ("&context=" + escape(document.context));
							  if (document.mmm_fo) document.write ("&amp;mmm_fo=1");
						   document.write ("'><\/scr"+"ipt>");
						//]]>--></script>

					<noscript> <div id='m3_tracker_1' style='position: absolute; left: 0px; top: 0px; visibility: hidden;'><img src='http://50.23.160.154/~mobileui/MobileUI/openx-server/www/delivery/ti.php?trackerid=1&cb=%%RANDOM_NUMBER%%' width='0' height='0' alt='' /></div></noscript>
					</textarea>
				</p>
				
				<h3><?php echo $this->lang->line("label_inventory_zone_invocation_tag_settings");?></h3>	
				
					<p>
                    	<label for="name"><?php echo $this->lang->line("label_inventory_zone_invocation_dont_show");?></label>
                      <input type="radio" checked="checked" name="1"   id="zone_type" value="1" class="sf" /> &nbsp;<?php echo $this->lang->line('label_yes');?><
						<input type="radio"  id="zone_type" name="1" value="2" class="sf" /> &nbsp;<?php echo $this->lang->line('label_no');?><
                    </p>
					<br/>
                    <p>
                    	<label for="email"><?php echo $this->lang->line("label_inventory_zone_invocation_target_frame");?></label>
                        <select>
							<option><?php echo $this->lang->line('label_default');?></option>
							<option><?php echo $this->lang->line('label_new_window');?></option>
							<option><?php echo $this->lang->line('label_same_window');?></option>
						</select>
                    </p>
                   <p>
                    	<label for="name"><?php echo $this->lang->line("label_inventory_zone_invocation_source");?></label>
                        <input type="text" name="name"  id="name" class="sf" />
                </p>
                    <p><label for="name"><?php echo $this->lang->line("label_inventory_zone_invocation_show_text");?></label>
                 
					    <input type="radio" checked="checked" name="2" id="zone_type" value="1" class="sf" /> &nbsp;<?php echo $this->lang->line('label_yes');?>
						<input type="radio"   id="zone_type" value="2" name="2" class="sf" />&nbsp;<?php echo $this->lang->line('label_no');?></p>
                    <p>
					<p>
					  <label for="name"><?php echo $this->lang->line("label_inventory_zone_invocation_dont_show_banner");?></label>
						<input type="radio" checked="checked"   id="zone_type" name="3" value="1" class="sf" /> &nbsp;<?php echo $this->lang->line('label_yes');?>
						<input type="radio"   id="zone_type" value="2" class="sf" name="3" /> &nbsp;<?php echo $this->lang->line('label_no');?></p>
                    
                    <p>
					<br/>
					<p>
                    	<label for="email"><?php echo $this->lang->line("label_inventory_zone_invocation_character_set");?></label>
                      
                        <select>
							<option selected="selected" value=""><?php echo $this->lang->line('label_auto_detect');?></option>
							<option value="ISO-8859-6">Arabic (ISO-8859-6)</option>
							<option value="Windows-1256">Arabic (Windows-1256)</option>
							<option value="ISO-8859-4">Baltic (ISO-8859-4)</option>
							<option value="Windows-1257">Baltic (Windows-1257)</option>
							<option value="ISO-8859-2">Central European (ISO-8859-2)</option>
							<option value="Windows-1250">Central European (Windows-1250)</option>
							<option value="GB18030">Chinese Simplified (GB18030)</option>
							<option value="GB2312">Chinese Simplified (GB2312)</option>
							<option value="HZ">Chinese Simplified (HZ)</option>
							<option value="Big5">Chinese Traditional (Big5)</option>
							<option value="ISO-8859-5">Cyrillic (ISO-8859-5)</option>
							<option value="KOI8-R">Cyrillic (KOI8-R)</option>
							<option value="Windows-1251">Cyrillic (Windows-1251)</option>
							<option value="ISO-8859-13">Estonian (ISO-8859-13)</option>
							<option value="ISO-8859-7">Greek (ISO-8859-7)</option>
							<option value="Windows-1253">Greek (Windows-1253)</option>
							<option value="ISO-8859-8-l">Hebrew (ISO Logical: ISO-8859-8-l)</option>
							<option value="ISO-8859-8">Hebrew (ISO:Visual: ISO-8859-8)</option>
							<option value="Windows-1255">Hebrew (Windows-1255)</option>
							<option value="EUC-JP">Japanese (EUC-JP)</option>
							<option value="Shift-JIS">Japanese (Shift-JIS)</option>
							<option value="EUC-KR">Korean (EUC-KR)</option>
							<option value="ISO-8859-15">Latin 9 (ISO-8859-15)</option>
							<option value="TIS-620">Thai (TIS-620)</option>
							<option value="ISO-8859-9">Turkish (ISO-8859-9)</option>
							<option value="Windows-1254">Turkish (Windows-1254)</option>
							<option value="UTF-8">Unicode (UTF-8)</option>
							<option value="Windows-1258">Vietnamese (Windows-1258)</option>
							<option value="ISO-8859-1">Western European (ISO-8859-1)</option>
							<option value="Windows-1252">Western European (Windows-1252)</option>
						</select>
                    </p>
					
					<p>
                    	<label for="email"><?php echo $this->lang->line("label_inventory_zone_invocation_support");?></label>
                    	
                        <select>
							<option><?php echo $this->lang->line('label_no');?></option>
							<option><?php echo $this->lang->line('label_generic');?></option>
							<option><?php echo $this->lang->line('label_rich_media_double_click');?></option>
							<option><?php echo $this->lang->line('label_rich_media_openx');?></option>
						</select>
                    </p>
					
					<p> <label for="name"><?php echo $this->lang->line("label_inventory_zone_invocation_include_comments");?> </label>
						<input type="radio" checked="checked" name="4"  id="zone_type" value="1" class="sf" />&nbsp;<?php echo $this->lang->line('label_yes');?>
						<input type="radio" name="4"  id="zone_type" value="2" class="sf" /> &nbsp;<?php echo $this->lang->line('label_no');?></p>
                    </p>
                    	<button><?php echo $this->lang->line("label_refresh");?></button>
                    </p>
                    
                </fieldset>
             <!--form-->
  	     </form>
		 
