<script type="text/javascript">
jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
      		
      	var res=document.getElementById('interchk').checked;
    	if(res==true)
    	{
    		jQuery("div[id='content']").slideDown("slow");
    	}
    	else
    	{
    		jQuery("div[id='content']").slideUp("slow");
    	}
      	
        jQuery("#form_mobile_targeting").validationEngine({promptPosition : "topLeft"});

        jQuery("div[id='intermediary']").slideUp("slow");
        jQuery("div[id='deviceos']").slideUp("slow");
        jQuery("div[id='devicemanuf']").slideUp("slow");
        jQuery("div[id='devicecap']").slideUp("slow");
        jQuery("div[id='deviceloc']").slideUp("slow");
        jQuery("div[id='targetagegroup']").slideUp("slow");
        jQuery("div[id='carrieroperator']").slideUp("slow");
});

function showTargetOS(){
    jQuery("div[id='deviceos']").slideDown("slow");
}

function hideTargetOS(){
    jQuery("div[id='deviceos']").slideUp("slow");    
}
function showTargetManu(){
    jQuery("div[id='devicemanuf']").slideDown("slow");
}

function hideTargetManu(){
    jQuery("div[id='devicemanuf']").slideUp("slow");
}
function showTargetCap(){
    jQuery("div[id='devicecap']").slideDown("slow");
}

function hideTargetCap(){
    jQuery("div[id='devicecap']").slideUp("slow");
}

function showGeoLoc(){
    jQuery("div[id='deviceloc']").slideDown("slow");
}

function hideGeoLoc(){
    jQuery("div[id='deviceloc']").slideUp("slow");
}

function showAgeGroup(){
    jQuery("div[id='targetagegroup']").slideDown("slow");
}

function hideAgeGroup(){
    jQuery("div[id='targetagegroup']").slideUp("slow");
}

function showGeoOperatcarrier(){
    jQuery("div[id='carrieroperator']").slideDown("slow");
}

function hideGeoOperatcarrier(){
    jQuery("div[id='carrieroperator']").slideUp("slow");
}

function SelectMoveRows(SS1,SS2,flag)
{
    var SelID='';
    var SelText='';
    // Move rows from SS1 to SS2 from bottom to top
    for (i=SS1.options.length - 1; i>=0; i--)
    {
        if (SS1.options[i].selected == true)
        {
            SelID=SS1.options[i].value;
            SelText=SS1.options[i].text;
            var newRow = new Option(SelText,SelID);
            SS2.options[SS2.length]=newRow;            
            SS1.options[i]=null;
        }
    }
    SelectSort(SS2);
    if(flag==1)
    {
        SelectAll(SS2);
    }
    else
    {
        SelectAll(SS1)
    }
}

/* Thefunction is added by shyam. Used to hide and diplay the div based on check box change */


function showTargetLocation()
{ 
	jQuery("div[id='intermediary']").slideDown("slow");
}
function hideTargetLocation()
{
	document.getElementById('interchk').checked=false;
	jQuery("div[id='intermediary']").slideUp("slow");
}



function selectClick()
{
	var res=document.getElementById('interchk').checked;
	if(res==true)
	{
		jQuery("div[id='content']").slideDown("slow");
	}
	else
	{
		jQuery("div[id='content']").slideUp("slow");
	}
	document.getElementById('callchk').disabled=false;
	document.getElementById('mapchk').disabled=false;
	document.getElementById('webchk').disabled=false;
	document.getElementById('callchk').checked=false;
	document.getElementById('mapchk').checked=false;
	document.getElementById('webchk').checked=false;
}
/* This function is used to select check box */
function selectCheck()
{
	var intm=document.getElementById('interchk').checked;
	var call = document.getElementById('callchk').checked;
	var map  = document.getElementById('mapchk').checked;
	var web  = document.getElementById('webchk').checked;
	if(!intm)
	{
		if(call)
		{
			
			document.getElementById('mapchk').disabled=true;
			document.getElementById('webchk').disabled=true;
				
		}
				
		if(map)
		{
			document.getElementById('callchk').disabled=true;
			document.getElementById('webchk').disabled=true;
		}
		
		
		if(web)
		{
			
			document.getElementById('mapchk').disabled=true;
			document.getElementById('callchk').disabled=true;
		}
		if(!call && !map && !web)
		{
			document.getElementById('callchk').disabled=false;
			document.getElementById('mapchk').disabled=false;
			document.getElementById('webchk').disabled=false;
			document.getElementById('callchk').checked=false;
			document.getElementById('mapchk').checked=false;
			document.getElementById('webchk').checked=false;
		} 
			
	}
	
			
}

function SelectSort(SelList)
{
    var ID='';
    var Text='';
    for (x=0; x < SelList.length - 1; x++)
    {
        for (y=x + 1; y < SelList.length; y++)
        {
            if (SelList[x].text > SelList[y].text)
            {
                // Swap rows
                ID=SelList[x].value;
                Text=SelList[x].text;
                SelList[x].value=SelList[y].value;
                SelList[x].text=SelList[y].text;
                SelList[y].value=ID;
                SelList[y].text=Text;                
            }
        }        
    }
}

function SelectAll(SelList)
{
    for (x=0; x < SelList.length; x++)
    {
        SelList[x].selected=true;
    }
}

function get_network(country)
{	
	if(country)
	{	
		jQuery.ajax({
			type: "POST",
			data: { country_code: country},
			url: '<?php echo site_url('advertiser/campaigns/region'); ?>',
			success: function(msg){ 
				jQuery("#carrier").html(msg);
			}
		}); 			
	}
	else
	{
		return false;	
	}			
}	

function goToList()
{
    document.location.href='<?php echo site_url('admin/inventory_campaigns'); ?>';
}

function check_filled_data()
{
	/* OS Check */
	/*var os = document.getElementById('osselect').checked;
	if(os==true)
	{
		var osdata = document.getElementById('destination_os').value;
		if(osdata=='')
		{
			jAlert('<center><?php echo $this->lang->line('label_select_specific_operating_system'); ?></center>');
			return false;
		}
	}
	/* Manufacturer Check */
	/*var manuf = document.getElementById('manufselect').checked;
	if(manuf==true)
	{
		var manufdata = document.getElementById('destination_manu').value;
		if(manufdata=='')
		{
			jAlert('<center><?php echo $this->lang->line('label_select_specific_manufacturers'); ?></center>');
			return false;
		}
	}
	/* Capability Check */
	/*var cap = document.getElementById('capableselect').checked;
	if(cap==true)
	{
		var capdata = document.getElementById('destination_cap').value;
		if(capdata=='')
		{
			jAlert('<center><?php echo $this->lang->line('label_select_specific_device_capability'); ?></center>');
			return false;
		}
	}
	/* Location Check */
	/*var loc = document.getElementById('locatselect').checked;
	if(loc==true)
	{
		var locdata = document.getElementById('destination_loc').value;
		if(locdata=='')
		{
			jAlert('<center><?php echo $this->lang->line('label_select_specific_geo_locations'); ?></center>');
			return false;
		}
	}
	
	/* Operator Check */
	/*var ope = document.getElementById('operatselect').checked;
	if(ope==true)
	{
		var opedata = document.getElementById('destination_ope').value;
		if(opedata=='')
		{
			jAlert('<center><?php echo $this->lang->line('label_select_specific_geo_operator'); ?></center>');
			return false;
		}
	}

/* Click to action check */

	
	var landing=document.getElementById('interchk').checked;
	var call = document.getElementById('callchk').checked;
	var map  = document.getElementById('mapchk').checked;
	var web  = document.getElementById('webchk').checked;
	
	if(landing && !call && !map && !web)
	{

		jAlert('<center><?php echo "Please select click to action"; ?></center>');
		return false;
	}


	
	/* Age Group Check */
	var age = document.getElementById('specage').checked;
	
	if(age==true)
	{
		var agedata = document.getElementsByName('selage[]');
		var hasChecked = false;
		
		for(var i=0;i<agedata.length;i++)
		{
			if(agedata[i].checked)
			{
				hasChecked = true;
				break;
			}
		}
		 if (!hasChecked)
        {
               jAlert('<center><?php echo "Please select specific Age Group"; ?></center>');
			return false;
        }
	}
	return true;
}
</script>

<h1 class="pageTitle">
	<?php echo $this->lang->line('label_targeting_title'); ?>
</h1>
<form name="form_mobile_targeting" id="form_mobile_targeting" enctype="multipart/form-data"
	action="<?php echo site_url('advertiser/campaigns/targeting_limitation_process'); ?>"
	method="post" onsubmit="return check_filled_data();">

	<div class="form_default" style="line-height: 25px;">
		<fieldset>
			<h2>
				<?php echo $this->lang->line('label_platform_devices'); ?>
			</h2>
			<p>
				<input type="hidden" name="campaign" id="campaign"
					value="<?php echo $sel_camp; ?>" /> <input type="radio" name="os"
					id="osall" onclick="hideTargetOS();" value="all" checked />
				<?php echo $this->lang->line('label_target_allos'); ?>
				<br /> <input type="radio" name="os" id="osselect"
					onclick="showTargetOS();" value="device_os" />
				<?php echo $this->lang->line('label_target_specos'); ?>
			
			
			<div id="deviceos">
				<?php if(count($deviceos)>0): ?>
				<table width="50%">
					<tr>
						<td align="center"><select name="source_os" id="source_os"
							multiple="multiple" size="<?php echo count($deviceos)+1; ?>">
								<optgroup
									label="<?php echo $this->lang->line('label_box_tot_os'); ?>"></optgroup>
								<?php
                                    foreach($deviceos as $os): ?>
								<option value="<?php echo $os->os_value; ?>">
									<?php echo $os->os_platform; ?>
								</option>
								<?php endforeach; ?>
						</select>
						</td>
						<td align="center" style="vertical-align: middle;"><input
							type="Button" value="Add >>" style="width: 100px"
							onClick="SelectMoveRows(document.form_mobile_targeting.source_os,document.form_mobile_targeting.destination_os,1)"><br>
							<br> <input type="Button" value="<< Remove" style="width: 100px"
							onClick="SelectMoveRows(document.form_mobile_targeting.destination_os,document.form_mobile_targeting.source_os)">
						</td>
						<td align="center"><select name="destination_os[]"
							id="destination_os" multiple="multiple"
							class="validate[required] sf"
							alt="<?php echo $this->lang->line('label_select_specific_operating_system'); ?>"
							size="<?php echo (count($deviceos)>0)?count($deviceos)+1:'5'; ?>">
								<optgroup
									label="<?php echo $this->lang->line('label_box_sel_os'); ?>"></optgroup>
						</select>
						</td>
					</tr>
				</table>
				<?php else: ?>
				<br />
				<div class="notification msgalert"
					style="width: 400px; height: 30px;">
					<?php echo $this->lang->line('label_no_os'); ?>
				</div>
				<?php endif; ?>
				<br />
			</div>
			<input type="radio" name="manufacturer" id="manufall" checked
				onclick="hideTargetManu();" value="all" />
			<?php echo $this->lang->line('label_target_allmanuf'); ?>
			<br /> <input type="radio" name="manufacturer" id="manufselect"
				onclick="showTargetManu();" value="device_manufacturer" />
			<?php echo $this->lang->line('label_target_specmanuf'); ?>
			<div id="devicemanuf">
				<?php if(count($devicemanuf)>0): ?>
				<table width="50%">
					<tr>
						<td align="center" valign="top"><select name="source_manu"
							id="source_manu" multiple="multiple"
							size="<?php echo count($devicemanuf)+1; ?>">
								<optgroup
									label="<?php echo $this->lang->line('label_box_tot_manu'); ?>"></optgroup>
								<?php
                                    foreach($devicemanuf as $manuf): ?>
								<option value="<?php echo $manuf->manufacturer_name; ?>">
									<?php echo $manuf->manufacturer_name; ?>
								</option>
								<?php endforeach; ?>
						</select>
						</td>
						<td align="center" style="vertical-align: middle;"><input
							type="Button" value="Add >>" style="width: 100px"
							onClick="SelectMoveRows(document.form_mobile_targeting.source_manu,document.form_mobile_targeting.destination_manu,1)"><br>
							<br> <input type="Button" value="<< Remove" style="width: 100px"
							onClick="SelectMoveRows(document.form_mobile_targeting.destination_manu,document.form_mobile_targeting.source_manu)">
						</td>
						<td align="center" valign="top"><select name="destination_manu[]"
							id="destination_manu" multiple="multiple"
							class="validate[required] sf"
							alt="<?php echo $this->lang->line('label_select_specific_manufacturers'); ?>"
							size="<?php echo (count($devicemanuf)>0)?count($devicemanuf)+1:'5'; ?>">
								<optgroup
									label="<?php echo $this->lang->line('label_box_sel_manu'); ?>"></optgroup>
						</select>
						</td>
					</tr>
				</table>
				<?php else: ?>
				<br />
				<div class="notification msgalert"
					style="width: 400px; height: 30px;">
					<?php echo $this->lang->line('label_no_manuf'); ?>
				</div>
				<?php endif; ?>
			</div>
			<br /> <br /> <input type="radio" name="capabilty" id="capableall"
				checked onclick="hideTargetCap();" value="all" />
			<?php echo $this->lang->line('label_target_allcap'); ?>
			<br /> <input type="radio" name="capabilty" id="capableselect"
				onclick="showTargetCap();" value="device_capability" />
			<?php echo $this->lang->line('label_target_speccap'); ?>
			<div id="devicecap">
				<?php if(count($devicecap)): ?>
				<table width="40%">
					<tr>
						<td align="center" valign="top"><select name="source_cap"
							id="source_cap" multiple="multiple"
							size="<?php echo count($devicecap)+1; ?>">
								<optgroup
									label="<?php echo $this->lang->line('label_box_tot_cap'); ?>"></optgroup>
								<?php foreach($devicecap as $cap): ?>
								<option value="<?php echo $cap->capability_value; ?>">
									<?php echo $cap->capability_name; ?>
								</option>
								<?php endforeach; ?>
						</select>
						</td>
						<td align="center"
							style="vertical-align: middle; padding-right: 5px;"><input
							type="Button" value="Add >>" style="width: 100px"
							onClick="SelectMoveRows(document.form_mobile_targeting.source_cap,document.form_mobile_targeting.destination_cap,1)"><br>
							<br> <input type="Button" value="<< Remove" style="width: 100px"
							onClick="SelectMoveRows(document.form_mobile_targeting.destination_cap,document.form_mobile_targeting.source_cap)">
						</td>
						<td align="center" valign="top"><select name="destination_cap[]"
							id="destination_cap" multiple="multiple"
							class="validate[required] sf"
							alt="<?php echo $this->lang->line('label_select_specific_device_capability'); ?>"
							size="<?php echo (count($devicecap))?count($devicecap)+1:'5'; ?>">
								<optgroup
									label="<?php echo $this->lang->line('label_box_sel_cap'); ?>"></optgroup>
						</select>
						</td>
					</tr>
				</table>
				<?php else: ?>
				<br />
				<div class="notification msgalert"
					style="width: 400px; height: 30px;">
					<?php echo $this->lang->line('label_no_cap'); ?>
				</div>
				<?php endif; ?>
			</div>
			</p>

			<h2>
				<?php echo 'Mobile Geography / Locations';//$this->lang->line('label_geography_operators'); ?>
			</h2>
			<p>
				<input type="radio" name="geolocation" id="locatall" checked
					onclick="hideGeoLoc();" value="geographic_all" />
				<?php echo $this->lang->line('label_target_allloc'); ?>
				<br /> <input type="radio" name="geolocation" id="locatselect"
					onclick="showGeoLoc();" value="geographic_locations" />
				<?php echo $this->lang->line('label_target_specloc'); ?>
			
			
			<div id="deviceloc">
				<?php if(count($geolocation)>0): ?>
				<table width="40%">
					<tr>
						<td align="center"><select name="source_loc" id="source_loc"
							multiple="multiple"
							size="<?php echo (count($geolocation)>=20)?'20':count($geolocation); ?>">
								<optgroup
									label="<?php echo $this->lang->line('label_box_tot_loc'); ?>"></optgroup>
								<?php foreach($geolocation as $location): ?>
								<option value="<?php echo $location->code; ?>">
									<?php echo $location->name; ?>
								</option>
								<?php endforeach; ?>
						</select>
						</td>
						<td align="center"
							style="vertical-align: middle; padding-right: 5px;"><input
							type="Button" value="Add >>" style="width: 100px"
							onClick="SelectMoveRows(document.form_mobile_targeting.source_loc,document.form_mobile_targeting.destination_loc,1)"><br>
							<br> <input type="Button" value="<< Remove" style="width: 100px"
							onClick="SelectMoveRows(document.form_mobile_targeting.destination_loc,document.form_mobile_targeting.source_loc)">
						</td>
						<td align="center"><select name="destination_loc[]"
							id="destination_loc" multiple="multiple"
							class="validate[required] sf"
							alt="<?php echo $this->lang->line('label_select_specific_geo_locations'); ?>"
							size="<?php echo (count($geolocation)>=20)?'20':count($geolocation); ?>">
								<optgroup
									label="<?php echo $this->lang->line('label_box_sel_loc'); ?>"></optgroup>
						</select>
						</td>
					</tr>
				</table>
				<?php else: ?>
				<br />
				<div class="notification msgalert"
					style="width: 400px; height: 30px;">
					<?php echo $this->lang->line('label_no_loc'); ?>
				</div>
				<?php endif; ?>
			</div>
			</p>

			<!-- Mobile Network Carrier Start-->
			<h2>
				<?php echo 'Network Carrier';//$this->lang->line('label_target_device_model'); ?>
			</h2>

			<p>
				<input type="radio" name="nwcarrier" id="carrieroperator" checked
					onclick="hideGeoOperatcarrier();" value="all" />
				<?php echo 'Target all Network Carrier';//$this->lang->line('label_target_allloc'); ?>
				<br /> <input type="radio" name="nwcarrier" id="carrieroperator"
					onclick="showGeoOperatcarrier();" value="mobileCarrierLimitation" />
				<?php echo 'Target specific Network Carrier';//$this->lang->line('label_target_specloc'); ?>
			
			
			<div id="carrieroperator">
				<ul id="networkcarriers">
					<?php if($geooperator_carrier): 
					foreach($geooperator_carrier as $geo_country):
					?>
					<li><?php echo $geo_country->country; ?> <?php $region_list = $this->mod_campaigns->get_region_operator($geo_country->country);	
			if(!empty($region_list)):?>
						<ul>
							<?php foreach($region_list as $r): ?>
							<li><input type="checkbox" name="network_carriers[]"
								id="network_carriers"
								value="<?php echo $geo_country->country.':'.$r->id; ?>">&nbsp;<?php echo $r->carriername; ?>
							</li>
							<?php endforeach; ?>
						</ul> <?php endif; ?>
					</li>
					<?php endforeach; 
					endif;
					?>
				</ul>
			</div>
			</p>
			<br />

			<h2>
				<?php echo $this->lang->line('label_target_device_model'); ?>
			</h2>
			<p>
				<label><?php echo $this->lang->line('label_device_name'); ?> </label>
				<select name="mobdevice">
					<option value="0">
						<?php echo $this->lang->line('label_no_limit'); ?>
					</option>
					<option value="==">
						<?php echo $this->lang->line('label_equal_to'); ?>
					</option>
					<option value="!=">
						<?php echo $this->lang->line('label_different_from'); ?>
					</option>
					<option value="=~">
						<?php echo $this->lang->line('label_contains'); ?>
					</option>
					<option value="!~">
						<?php echo $this->lang->line('label_not_contain'); ?>
					</option>
				</select> <input type="text" name="device_name" />
			</p>
			<br />




			<!-- ////////////////////////////////   The below code is added by shyam /////////////////////////////-->


			<h2>
				<?php echo "Location Targeting" ?>
			</h2>
			<br> <input type="radio" id="allLoc" checked
				onchange="hideTargetLocation()" name="loc" value="all"
				<?php echo set_radio('loc','all',true);  ?> /> All locations </br> 
				<input type="radio" id="specLoc" onchange="showTargetLocation()" name="loc"
				value="spec" <?php echo set_radio('loc','spec'); ?> /> Target
			specific locations
			<div id="intermediary"
				style="margin-left: 0in; background: #FCEDF3; width: 4in;">

				<input id="interchk" type="checkbox" onchange="selectClick()"
					name="inter" value="intermed"
					<?php echo set_checkbox('inter', 'intermed'); ?> /> Show Intermidiary page </br> 
				
				<div id="content" style="background: #FCEDF3;">
				<textarea rows="3" cols="50" name="txtarea" value="Description"></textarea>
				<p>
				<label for="name">Upload-Main Rectangle-XL
				 </label>
			 <input class="sf" type="file" name="rect_banner_xl" alt="Please select Rect banner" value="rect_banner_xl"/>
				</p>
				<p>
				<label for="name">Upload-Main Rectangle-L</label>
			 <input class="sf" type="file" name="rect_banner_l" alt="Please select Rect banner" value="rect_banner_l"/>
				</p>
				<p>
				<label for="name">Upload-Main Rectangle-M</label>
			 <input class="sf" type="file" name="rect_banner_m" alt="Please select Rect banner" value="rect_banner_m"/>
				</p>
				<p>
				<label for="name">Upload-Main Rectangle-S</label>
			 <input class="sf" type="file" name="rect_banner_s" alt="Please select Rect banner" value="rect_banner_s"/>
				</p>
				</div>
					<input id="callchk" type="checkbox" onchange="selectCheck()" name="callcheck" value="call"
					<?php echo set_checkbox('callcheck', 'call',false); ?> /> Click to
				Call &nbsp;&nbsp; <input id="mapchk" type="checkbox"
					onchange="selectCheck()" name="mapcheck" value="map"
					<?php echo set_checkbox('mapcheck', 'map',false); ?> /> Click to
				Map &nbsp;&nbsp; <input id="webchk" type="checkbox"
					onchange="selectCheck()" name="webcheck" value="web"
					<?php echo set_checkbox('webcheck', 'web',false); ?> /> Click to
				Web
			</div>
			<br> <br>

			<h2>
				<?php echo $this->lang->line('label_target_demographics'); ?>
			</h2>
			<br />
			<div style="width: 400px; height: 250px; line-height: 25px;">
				<div style="float: left;">
					<h4>
						<?php echo $this->lang->line('label_target_gender'); ?>
					</h4>
					<input type="radio" name="gender" id="allgender" checked
						value="all" />
					<?php echo $this->lang->line('label_target_all_user'); ?>
					<br /> <input type="radio" name="gender" id="male" value="m" />
					<?php echo $this->lang->line('label_target_male_only'); ?>
					<br /> <input type="radio" name="gender" id="female" value="f" />
					<?php echo $this->lang->line('label_target_female_only'); ?>
					<br />
				</div>
				<div style="float: right;">
					<h4>
						<?php echo $this->lang->line('label_target_age'); ?>
					</h4>
					<input type="radio" name="agegroup" id="allage" checked
						onclick="hideAgeGroup();" value="all" />
					<?php echo $this->lang->line('label_target_ageall'); ?>
					<br /> <input type="radio" name="agegroup" id="specage"
						onclick="showAgeGroup();" value="specific" />
					<?php echo $this->lang->line('label_target_agespec'); ?>
					<br />
					<?php if(count($agegroup)>0): ?>
					<div id="targetagegroup">
						<?php foreach($agegroup as $age): ?>
						<input type="checkbox" name="selage[]"
							id="selage<?php echo $age->from.'-'.$age->to; ?>"
							value="<?php echo $age->from.'-'.$age->to; ?>" />
						<?php echo $age->from.' - '.$age->to; ?>
						&nbsp;&nbsp; <br />
						<?php endforeach; ?>
					</div>
					<?php else: ?>
					<div id="targetagegroup">
						<p>
							<?php echo $this->lang->line('label_sorry_records_not_found'); ?>
							<br /> <a
								href="<?php echo site_url('admin/settings_client_profile/add_client_profile'); ?>">Click
								to Add Age Group</a>
						</p>
					</div>
					<?php endif; ?>
				</div>
			</div>

			<div>
				<button>
					<?php echo $this->lang->line('label_update_targeting'); ?>
				</button>
				<button type="button" style="margin-left: 10px;"
					onclick="javascript: goToList();">
					<?php echo $this->lang->line('label_cancel'); ?>
				</button>
			</div>
		</fieldset>
	</div>
	<!--form-->
</form>
<script type="text/javascript">
	jQuery(document).ready(function(){
		// first example
		jQuery("#networkcarriers").treeview({
			collapsed: true,
			persist: "location"
		});
	});
</script>
