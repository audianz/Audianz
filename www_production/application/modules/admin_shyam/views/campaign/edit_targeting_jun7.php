<script type="text/javascript" src="<?php echo base_url();?>assets/js/custom/dropdown.js" ></script>
<script type="text/javascript">
jQuery(document).ready(function(){
		
        // binds form submission and fields to the validation engine
        jQuery("#form_mobile_targeting").validationEngine({promptPosition : "topLeft"});

        /* jQuery("div[id='deviceos']").slideUp("veryfast");
        jQuery("div[id='devicemanuf']").slideUp("veryfast");
        jQuery("div[id='devicecap']").slideUp("veryfast");
        jQuery("div[id='deviceloc']").slideUp("veryfast");
        jQuery("div[id='deviceoperator']").slideUp("veryfast");
        jQuery("div[id='targetagegroup']").slideUp("veryfast"); */

        var updateos = jQuery('input[name=os]:checked', '#form_mobile_targeting').val();
        if(updateos=='all'){
            hideTargetOS();
        } else {
            showTargetOS();
        }

        var updatemanuf = jQuery('input[name=manufacturer]:checked', '#form_mobile_targeting').val();
        if(updatemanuf=='all'){
            hideTargetManu();
        } else {
            showTargetManu();
        }

        var updatecap = jQuery('input[name=capabilty]:checked', '#form_mobile_targeting').val();
        if(updatecap=='all'){
            hideTargetCap();
        } else {
            showTargetCap();
        }

        var updateloc = jQuery('input[name=geolocation]:checked', '#form_mobile_targeting').val();
        if(updateloc=='geographic_all'){
            hideGeoLoc();
        } else {
            showGeoLoc();
        }

        var updateope = jQuery('input[name=geooperator]:checked', '#form_mobile_targeting').val();
        if(updateope=='operator_all'){
            hideGeoOperat();
        } else {
            showGeoOperat();
        }
        
        var updateope = jQuery('input[name=nwcarrier]:checked', '#form_mobile_targeting').val();
        if(updateope=='all'){
            hideGeoOperatcarrier();
        } else {
            showGeoOperatcarrier();
        }
        
		/*var updateope = jQuery('input[name=geooperatorcarrier]:checked', '#form_mobile_targeting').val();
        if(updateope=='all'){
            hideGeoOperatcarrier();
        } else {
            showGeoOperatcarrier();
        }*/

        var updateage = jQuery('input[name=agegroup]:checked', '#form_mobile_targeting').val();
        if(updateage=='all'){
            hideAgeGroup();
        } else {
            showAgeGroup();
        }
		
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

function showGeoOperat(){
    jQuery("div[id='deviceoperator']").slideDown("slow");
}

function hideGeoOperat(){
    jQuery("div[id='deviceoperator']").slideUp("slow");
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

function goToList()
{
    document.location.href='<?php echo site_url("advertiser/campaigns"); ?>';
}

</script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/custom/stickyFloat.js" ></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery(".left").css("overflow","visible");
	jQuery('#menu').stickyfloat({ duration: 400 });
});
</script>



<h1 class="pageTitle"><?php echo $this->lang->line('label_targeting_title'); ?></h1>
<?php if(count($target)>0):
    $target = $target[0]; ?>    
<?php endif; ?>
<form name="form_mobile_targeting" id="form_mobile_targeting" action="<?php echo site_url('admin/inventory_campaigns/targeting_limitation_process'); ?>" method="post" onsubmit="return check_filled_data();">
    
<div class="form_default" style="line-height: 25px;position:relative">
<div id="menu" class="menu">
				
				<div class="menu_title">
        			<h4><b><?php echo ucfirst($camp_summary->campaignname); ?> <?php echo $this->lang->line('label_full_details');?></b></h4>	            
        		</div>
				<table cellspacing="0" cellpadding="0" width="100%" id="userlist" class="sTable3 scroll_table">
            <thead>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $this->lang->line('label_campaign_name');?> </td>
                    <td><?php echo ucfirst($camp_summary->campaignname);?> </td>
                  </tr>
                <tr class="even">
                	<td><?php echo $this->lang->line('label_date');?> </td>
                    <td><?php echo date_format(date_create($camp_summary->activate_time),'jS F Y');?> </td>    
				</tr>
				<tr>
                  	<td><?php echo $this->lang->line('label_budget');?></td>
                    <td><i>$<?php echo $camp_summary->dailybudget;?> </i></td>
                </tr>
              </tbody>
        </table>
	<?php //echo '<pre>';
		//print_r($target);
		?>		
			<div class="menu_title">
        			<h4><b><?php echo ucfirst($camp_summary->campaignname); ?> <?php echo $this->lang->line('label_ad_target_summary'); ?></b></h4>	            
        	</div>
			<table cellspacing="0" cellpadding="0" width="100%" id="userlist" class="sTable3 scroll_table">
            <thead>
            </thead>
            <tbody>
				<tr>
					<td colspan="2"><span class="scroll_subtitile"><?php echo $this->lang->line('label_platform_devices'); ?></span></td>
				</tr>
                <tr>
                    <td><?php echo $this->lang->line("label_device_operating_system"); ?></td>
				<?php //print_r($target); ?>
                    <td>
							<?php if($target->device_type ==='all'):
											echo $this->lang->line('label_all_device_os');
								elseif($target->device_type=='device_os'):
								?>
									<b><a  href=""  id="searchlink" rel="subcontent"><?php echo $this->lang->line('label_device_os_list');?><span class="popup_icon"></span></a></b>
									<div id="subcontent" class="popup_box"> 
									<h4><?php echo $this->lang->line('label_device_os_title'); ?></h4>
									<div class="list_data">
								<?php			$dos = explode(',',$target->devices);
											for($i=0;$i<count($deviceos);$i++):
												if(in_array($deviceos[$i]->os_value,$dos)):
												echo	$deviceos[$i]->os_platform;
												echo '<br/> ';
												endif;
											endfor; ?>
											</div>
										</div>
						<?php	endif;
							?>
					 </td>
                  </tr>
				  <tr class="even">
                    <td><?php echo $this->lang->line('label_manufacturer_devices'); ?></td>
				<?php //print_r($target); ?>
                    <td>
							<?php if($target->manufacturer_type ==='all'):
											echo $this->lang->line('label_all_manufacturers');
								elseif($target->manufacturer_type=='device_manufacturer'):
								?>
									<b><a  href="#"  id="searchlink1" rel="subcontent1"><?php echo $this->lang->line('label_device_manufacturers_list');?><span class="popup_icon"></span></a></b>
									<div id="subcontent1" class="popup_box"> 
										<h4><?php echo $this->lang->line('label_device_manufacturer_title'); ?></h4>
									<div class="list_data">
								<?php			$dman = explode(',',$target->manufacturer);
											for($i=0;$i<count($devicemanuf);$i++):
												if(in_array($devicemanuf[$i]->manufacturer_name,$dman)):
												echo	$devicemanuf[$i]->manufacturer_name;
												echo '<br/> ';
												endif;
											endfor; ?>
											</div>
										</div>
						<?php	endif;
							?>
					 </td>
                  </tr>
				  <tr>
                  <td><?php echo $this->lang->line('label_device_capablities');?></td>
				  <td>
							<?php if($target->capability_type ==='all'):
											echo  $this->lang->line('label_all_device_capablities');
								elseif($target->capability_type=='device_capability'):
								?>
									<b><a  href="#"  id="searchlink2" rel="subcontent2"><?php echo $this->lang->line('label_device_capablities_list'); ?><span class="popup_icon"></span></a></b>
									<div id="subcontent2" class="popup_box"> 
									<h4><?php echo $this->lang->line('label_device_capablities');?></h4>
									<div class="list_data">
								<?php			$dcap = explode(',',$target->capability);
											for($i=0;$i<count($devicecap);$i++):
												if(in_array($devicecap[$i]->capability_value,$dcap)):
												echo	$devicecap[$i]->capability_name;
												echo '<br/> ';
												endif;
											endfor; ?>
											</div>
										</div>
						<?php	endif;
							?>
					 </td>
                  </tr>
				  
				  
				  	<tr>
					<td colspan="2"><span class="scroll_subtitile"><?php echo $this->lang->line('label_mobile_geo_ope');?></span></td>
				</tr>
				  
				  <tr>
                  <td><?php echo $this->lang->line('label_geographic_locations');?></td>
				  <td>
							<?php if($target->location_type ==='geographic_all'):
											echo $this->lang->line('label_all_locations');
								elseif($target->location_type=='geographic_locations'):
								?>
									<b><a  href="#"  id="searchlink3" rel="subcontent3"><?php echo $this->lang->line('label_geographic_loc_list');?><span class="popup_icon"></span></a></b>
									<div id="subcontent3" class="popup_box"> 
									<h4><?php echo $this->lang->line('label_geographic_locations');?></h4>
									<div class="list_data">
								<?php			$gloc = explode(',',$target->locations);
											for($i=0;$i<count($geolocation);$i++):
												if(in_array($geolocation[$i]->code,$gloc)):
												echo	$geolocation[$i]->name;
												echo '<br/> ';
												endif;
											endfor; ?>
											</div>
										</div>
						<?php	endif;
							?>
					 </td>
                  </tr>
				  
				 <tr class="even">
                  <td><?php echo $this->lang->line('label_geographic_operators');  ?></td>
				  <td>
							<?php if($target->operator_type ==='operator_all'):
							
											echo $this->lang->line('label_all_operators');
								elseif($target->operator_type=='geographic_operators'):
								
								?>
									<b><a  href="#"  id="searchlink4" rel="subcontent4"><?php echo $this->lang->line('label_geo_operators_list'); ?><span class="popup_icon"></span></a></b>
									<div id="subcontent4" class="popup_box"> 
									<h4><?php echo $this->lang->line('label_geographic_operators'); ?></h4>
									<div class="list_data">
								<?php			$gope = explode(',',$target->operators);
								               for($i=0;$i<count($geooperator);$i++):
												if(in_array($geooperator[$i]->telecom_value,$gope)):
												echo $geooperator[$i]->telecom_name;
												echo '<br/> ';
												endif;
											endfor;
											
																			
											 ?>
											</div>
										</div>
						<?php	endif;
							?>
					 </td>
                  </tr>
				  
				  <tr>
					<td colspan="2"><span class="scroll_subtitile"><?php echo $this->lang->line('label_device_model');?> Device Model</span></td>
				</tr>
				
				<tr>
                    <td><?php echo $this->lang->line('label_device_name');?></td>
                    <td>
					<?php 
					if($target->model_type=='0'): 
							echo $this->lang->line('label_no_limit');
						else:
							$model_type_arr = array('=='=>'Is equals to',
									'!='=>'Is different from',
									'=~'=>'Contains',
									'!~'=>'Does not Contain'
									);
							if(array_key_exists($target->model_type,$model_type_arr)):
								echo $target->model.'('.$model_type_arr[$target->model_type].')';
							endif;
						endif;
						?> </td>
                  </tr>
				  
				  <tr>
					<td colspan="2"><span class="scroll_subtitile"><?php echo $this->lang->line('label_demographics');?></span></td>
				</tr>
				
				  <tr>
                    <td><?php echo $this->lang->line('label_gender');?></td>
                    <td>
					<?php 
							if($target->gender_type =='all'):
								echo $this->lang->line('label_all');
							elseif($target->gender_type =='m'):
								echo ucfirst($this->lang->line('label_male'));	
							elseif($target->gender_type =='f'):
								echo ucfirst($this->lang->line('label_female'));	
							endif;
						?> </td>
                  </tr>
				  
				  <tr class="even">
                    <td><?php echo $this->lang->line('label_age_groups');?></td>
                  <td>
							<?php if($target->ages_type ==='all'):
											echo $this->lang->line('label_all_age_groups');
								elseif($target->ages_type=='specific'):
								$selage = explode(',',$target->ages);
											for($i=0;$i<count($agegroup);$i++):
												$totage = $agegroup[$i]->from.'-'.$agegroup[$i]->to;
												if(in_array($totage,$selage)):
												echo	$totage;
												if($i<count($selage)):
												echo ', ';
												endif;
												endif;
											endfor; ?>
										</div>
						<?php	endif;
							?>
					 </td>
                  </tr>
				
              </tbody>
        </table>
		
</div>
<script type="text/javascript">
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
	/*(var ope = document.getElementById('operatselect').checked;
	if(ope==true)
	{
		var opedata = document.getElementById('destination_ope').value;
		if(opedata=='')
		{
			jAlert('<center><?php echo $this->lang->line('label_select_specific_geo_operator'); ?></center>');
			return false;
		}
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
<fieldset>
<h2><?php echo $this->lang->line('label_platform_devices'); ?></h2>
<p>
    <input type="hidden" name="campaign" id="campaign" value="<?php echo $sel_camp; ?>" />
    <input type="radio" name="os" id="osall" onclick="hideTargetOS();" value="all" <?php echo ($target->device_type=='all')?'checked':''; ?> /> <?php echo $this->lang->line('label_target_allos'); ?>
    <br/>
    <input type="radio" name="os" id="osselect" onclick="showTargetOS();" value="device_os" <?php echo ($target->device_type=='device_os')?'checked':''; ?> /> <?php echo $this->lang->line('label_target_specos'); ?>
    <div id="deviceos">
    <?php if(count($deviceos)>0): ?>
        <table width="50%">
                <tr>
                        <td align="center">
                                <select name="source_os" id="source_os" multiple="multiple" size="<?php echo count($deviceos)+1; ?>">
                                    <optgroup label="<?php echo $this->lang->line('label_box_tot_os'); ?>"></optgroup>
                                <?php $dos = explode(',',$target->devices);
                                    for($i=0;$i<count($deviceos);$i++):
                                        if(!in_array($deviceos[$i]->os_value,$dos)): ?>
                                        <option value="<?php echo $deviceos[$i]->os_value; ?>"><?php echo $deviceos[$i]->os_platform; ?></option>
                                <?php endif;
                                endfor;
                                ?>
                                </select>                                
                        </td>
                        <td align="center" style="vertical-align:middle;">
                            <input type="Button" value="Add >>" style="width:100px" onClick="SelectMoveRows(document.form_mobile_targeting.source_os,document.form_mobile_targeting.destination_os,1)"><br>
                            <!-- <a href="javascript:void(0);" onClick="SelectMoveRows(document.form_mobile_targeting.source_os,document.form_mobile_targeting.destination_os,1)" class="iconlink3"><img src="<?php echo base_url(); ?>/assets/images/icons/small/white/plus.png" class="mgright5" alt="" /> <span>&nbsp;&nbsp;&nbsp;Add&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a><br> -->
                            <br>
                            <input type="Button" value="<< Remove" style="width:100px" onClick="SelectMoveRows(document.form_mobile_targeting.destination_os,document.form_mobile_targeting.source_os)">
                            <!-- <a href="javascript:void(0);" onClick="SelectMoveRows(document.form_mobile_targeting.destination_os,document.form_mobile_targeting.source_os)" class="iconlink3"><img src="<?php echo base_url(); ?>/assets/images/icons/small/white/minus.png" class="mgright5" alt="" /> <span>&nbsp;Remove&nbsp;</span></a><br> -->
                        </td>
                        <td align="center">
                                <select name="destination_os[]" id="destination_os" class="validate[required] sf" alt="<?php echo $this->lang->line('label_select_specific_operating_system'); ?>" multiple="multiple" size="<?php echo (count($deviceos)>0)?count($deviceos)+1:'5'; ?>">
                                    <optgroup label="<?php echo $this->lang->line('label_box_sel_os'); ?>"></optgroup>
                                    <?php   $dos = explode(',',$target->devices);
                                            for($i=0;$i<count($deviceos);$i++):
                                            if(in_array($deviceos[$i]->os_value,$dos)): ?>
                                                    <option value="<?php echo $deviceos[$i]->os_value; ?>" selected="selected"><?php echo $deviceos[$i]->os_platform; ?></option>
                                            <?php endif;
                                        endfor; ?>
                                </select>
                        </td>
                </tr>
        </table>
    	<?php else: ?>
	<br/>
	<div class="notification msgalert" style="width:400px;height:30px;">
		<?php echo $this->lang->line('label_no_os'); ?>
	</div>
	<?php endif; ?>    
    <br/>
    </div>
    <input type="radio" name="manufacturer" id="manufall" onclick="hideTargetManu();" value="all" <?php echo ($target->manufacturer_type=='all')?'checked':''; ?> /> <?php echo $this->lang->line('label_target_allmanuf'); ?>
    <br/>
    <input type="radio" name="manufacturer" id="manufselect" onclick="showTargetManu();" value="device_manufacturer" <?php echo ($target->manufacturer_type=='device_manufacturer')?'checked':''; ?> /> <?php echo $this->lang->line('label_target_specmanuf'); ?>
    <div id="devicemanuf">
    <?php if(count($devicemanuf)>0): ?>
        <table width="50%">
                <tr>
                        <td align="center" valign="top">
                                <select name="source_manu" id="source_manu" multiple="multiple" size="<?php echo count($devicemanuf)+1; ?>">
                                    <optgroup label="<?php echo $this->lang->line('label_box_tot_manu'); ?>"></optgroup>
                                <?php $dman = explode(',',$target->manufacturer);
                                    for($j=0;$j<count($devicemanuf);$j++):
                                        if(!in_array($devicemanuf[$j]->manufacturer_name,$dman)): ?>
                                                <option value="<?php echo $devicemanuf[$j]->manufacturer_name; ?>"><?php echo $devicemanuf[$j]->manufacturer_name; ?></option>
                                        <?php endif;
                                    endfor; ?>
                                </select>                                
                        </td>
                        <td align="center" style="vertical-align:middle;">
                            <input type="Button" value="Add >>" style="width:100px" onClick="SelectMoveRows(document.form_mobile_targeting.source_manu,document.form_mobile_targeting.destination_manu,1)"><br>
                            <br>
                            <input type="Button" value="<< Remove" style="width:100px" onClick="SelectMoveRows(document.form_mobile_targeting.destination_manu,document.form_mobile_targeting.source_manu)">
                        </td>
                        <td align="center" valign="top">
                                <select name="destination_manu[]" id="destination_manu" multiple="multiple" class="validate[required] sf" alt="<?php echo $this->lang->line('label_select_specific_manufacturers'); ?>" size="<?php echo (count($devicemanuf)>0)?count($devicemanuf)+1:'5'; ?>">
                                    <optgroup label="<?php echo $this->lang->line('label_box_sel_manu'); ?>"></optgroup>
                                    <?php $dman = explode(',',$target->manufacturer);
                                    for($j=0;$j<count($devicemanuf);$j++):
                                        if(in_array($devicemanuf[$j]->manufacturer_name,$dman)): ?>
                                                <option value="<?php echo $devicemanuf[$j]->manufacturer_name; ?>" selected="selected"><?php echo $devicemanuf[$j]->manufacturer_name; ?></option>
                                        <?php endif;
                                    endfor; ?>
                                </select>
                        </td>
                </tr>
        </table>
    <?php else: ?>
	<br/>
	<div class="notification msgalert" style="width:400px;height:30px;">
		<?php echo $this->lang->line('label_no_manuf'); ?>
	</div>
	<?php endif; ?>    
    </div>
    <br/>
    <br/>
    <input type="radio" name="capabilty" id="capableall" onclick="hideTargetCap();" value="all" <?php echo ($target->capability_type=='all')?'checked':''; ?> /> <?php echo $this->lang->line('label_target_allcap'); ?>
    <br/>
    <input type="radio" name="capabilty" id="capableselect" onclick="showTargetCap();" value="device_capability" <?php echo ($target->capability_type=='device_capability')?'checked':''; ?> /> <?php echo $this->lang->line('label_target_speccap'); ?>
    <div id="devicecap">
    <?php if(count($devicecap)): ?>
        <table width="40%">
               <tr>
                        <td align="center" valign="top">
                                <select name="source_cap" id="source_cap" multiple="multiple" size="<?php echo count($devicecap)+1; ?>">
                                    <optgroup label="<?php echo $this->lang->line('label_box_tot_cap'); ?>"></optgroup>
                                    <?php $dcap = explode(',',$target->capability);
                                     for($j=0;$j<count($devicecap);$j++):
                                        if(!in_array($devicecap[$j]->capability_value,$dcap)): ?>
                                                <option value="<?php echo $devicecap[$j]->capability_value; ?>"><?php echo $devicecap[$j]->capability_name; ?></option>
                                        <?php endif;
                                    endfor; ?>
                                </select>                            
                        </td>
                        <td align="center" style="vertical-align:middle;padding-right: 5px;">
                            <input type="Button" value="Add >>" style="width:100px" onClick="SelectMoveRows(document.form_mobile_targeting.source_cap,document.form_mobile_targeting.destination_cap,1)"><br>
                            <br>
                            <input type="Button" value="<< Remove" style="width:100px" onClick="SelectMoveRows(document.form_mobile_targeting.destination_cap,document.form_mobile_targeting.source_cap)">
                        </td>
                        <td align="center" valign="top">
                                <select name="destination_cap[]" id="destination_cap" multiple="multiple" class="validate[required] sf" alt="<?php echo $this->lang->line('label_select_specific_device_capability'); ?>" size="<?php echo (count($devicecap))?count($devicecap)+1:'5'; ?>">
                                    <optgroup label="<?php echo $this->lang->line('label_box_sel_cap'); ?>"></optgroup>
                                     <?php $dcap = explode(',',$target->capability);
                                     for($j=0;$j<count($devicecap);$j++):
                                        if(in_array($devicecap[$j]->capability_value,$dcap)): ?>
                                                <option value="<?php echo $devicecap[$j]->capability_value; ?>" selected="selected"><?php echo $devicecap[$j]->capability_name; ?></option>
                                        <?php endif;
                                    endfor; ?>
                                </select>
                        </td>
                </tr>
        </table>
    	<?php else: ?>
    	<br/>
    	<div class="notification msgalert" style="width:400px;height:30px;">
    		<?php echo $this->lang->line('label_no_cap'); ?>
    	</div>
        <?php endif; ?>    
    </div>
</p>

<h2><?php echo $this->lang->line('label_geography_operators'); ?></h2>
<p>
    <input type="radio" name="geolocation" id="locatall" onclick="hideGeoLoc();" value="geographic_all" <?php echo ($target->location_type=='geographic_all')?'checked':''; ?> /> <?php echo $this->lang->line('label_target_allloc'); ?>
    <br/>
    <input type="radio" name="geolocation" id="locatselect" onclick="showGeoLoc();" value="geographic_locations" <?php echo ($target->location_type=='geographic_locations')?'checked':''; ?> /> <?php echo $this->lang->line('label_target_specloc'); ?>
    <div id="deviceloc">
    <?php if(count($geolocation)>0): ?>
    <table width="40%">
            <tr>
                <td align="center">
                            <select name="source_loc" id="source_loc" multiple="multiple" size="<?php echo (count($geolocation)>=20)?'20':count($geolocation); ?>">
                                <optgroup label="<?php echo $this->lang->line('label_box_tot_loc'); ?>"></optgroup>
                                <?php $gloc = explode(',',$target->locations);
                                     for($k=0;$k<count($geolocation);$k++):
                                        if(!in_array($geolocation[$k]->code,$gloc)): ?>
                                                <option value="<?php echo $geolocation[$k]->code; ?>"><?php echo $geolocation[$k]->name; ?></option>
                                        <?php endif;
                                endfor; ?>
                            </select>                    
                    </td>
                    <td align="center" style="vertical-align:middle;padding-right: 5px;">
                            <input type="Button" value="Add >>" style="width:100px" onClick="SelectMoveRows(document.form_mobile_targeting.source_loc,document.form_mobile_targeting.destination_loc,1)"><br>
                            <br>
                            <input type="Button" value="<< Remove" style="width:100px" onClick="SelectMoveRows(document.form_mobile_targeting.destination_loc,document.form_mobile_targeting.source_loc)">
                    </td>
                    <td align="center">
                            <select name="destination_loc[]" id="destination_loc" multiple="multiple" class="validate[required] sf" alt="<?php echo $this->lang->line('label_select_specific_geo_locations'); ?>" size="<?php echo (count($geolocation)>=20)?'20':count($geolocation); ?>">
                                <optgroup label="<?php echo $this->lang->line('label_box_sel_loc'); ?>"></optgroup>
                                <?php $gloc = explode(',',$target->locations);
                                     for($k=0;$k<count($geolocation);$k++):
                                        if(in_array($geolocation[$k]->code,$gloc)): ?>
                                                <option value="<?php echo $geolocation[$k]->code; ?>" selected="selected"><?php echo $geolocation[$k]->name; ?></option>
                                        <?php endif;
                                endfor; ?>
                            </select>
                    </td>
            </tr>
    </table>
    <?php else: ?>
	<br/>
	<div class="notification msgalert" style="width:400px;height:30px;">
		<?php echo $this->lang->line('label_no_loc'); ?>
	</div>
	<?php endif; ?> 
    </div>
</p>
<p><?php //print_r($target);?>
    <input type="radio" name="geooperator" id="operatall" onclick="hideGeoOperat();" value="operator_all" <?php echo ($target->operator_type=='operator_all')?'checked':''; ?> /> <?php echo $this->lang->line('label_target_allope'); ?>
    <br/>
    <input type="radio" name="geooperator" id="operatselect" onclick="showGeoOperat();" value="geographic_operators" <?php echo ($target->operator_type=='geographic_operators')?'checked':''; ?> /> <?php echo $this->lang->line('label_target_specope'); ?>
    <div id="deviceoperator">
    <?php if(count($geooperator)>0): ?>
    <table width="40%">
            <tr>
                    <td align="center">                        
                            <select name="source_ope" id="source_ope" multiple="multiple" size="<?php echo (count($geooperator)>=20)?'20':count($geooperator); ?>">
                                <optgroup label="<?php echo $this->lang->line('label_box_tot_ope'); ?>"></optgroup>
                                <?php $gope = explode(',',$target->operators);
                                     for($l=0;$l<count($geooperator);$l++):
                                        if(!in_array($geooperator[$l]->telecom_value,$gope)): ?>
                                                <option value="<?php echo $geooperator[$l]->telecom_value; ?>"><?php echo $geooperator[$l]->telecom_name; ?></option>
                                        <?php endif;
                                endfor; ?>
                            </select>
                    </td>
                    <td align="center" style="vertical-align:middle;padding-right: 5px;">
                            <input type="Button" value="Add >>" style="width:100px" onClick="SelectMoveRows(document.form_mobile_targeting.source_ope,document.form_mobile_targeting.destination_ope,1)"><br>
                            <br>
                            <input type="Button" value="<< Remove" style="width:100px" onClick="SelectMoveRows(document.form_mobile_targeting.destination_ope,document.form_mobile_targeting.source_ope)">
                    </td>
                    <td align="center">
                            <select name="destination_ope[]" id="destination_ope" multiple="multiple"class="validate[required] " alt="<?php echo $this->lang->line('label_select_specific_geo_operator');?>" size="<?php echo (count($geooperator)>=20)?'20':count($geooperator); ?>">
                                <optgroup label="<?php echo $this->lang->line('label_box_sel_ope'); ?>"></optgroup>
                                <?php $gope = explode(',',$target->operators);
                                     for($l=0;$l<count($geooperator);$l++):
                                        if(in_array($geooperator[$l]->telecom_value, $gope)): ?>
                                                <option value="<?php echo $geooperator[$l]->telecom_value; ?>" selected="selected"><?php echo $geooperator[$l]->telecom_name; ?></option>
                                        <?php endif;
                                endfor; ?>
                            </select>
                    </td>
            </tr>
    </table>
    <?php else: ?>
	<br/>
	<div class="notification msgalert" style="width:400px;height:30px;">
		<?php echo $this->lang->line('label_no_ope'); ?>
	</div>
	<?php endif; ?>
    </div>
</p>

<!-- Mobile Network Carrier Start-->
<h2><?php echo 'Network Carrier';//$this->lang->line('label_target_device_model'); ?></h2>

<p>
    <input type="radio" name="nwcarrier" id="carrieroperator" checked onclick="hideGeoOperatcarrier();" value="all" <?php echo ($target->carrier_type=='operator_all')?'checked':''; ?> /> <?php echo 'Target all Network Carrier';//$this->lang->line('label_target_allloc'); ?>
    <br/>
    <input type="radio" name="nwcarrier" id="carrieroperator" onclick="showGeoOperatcarrier();" value="mobileCarrierLimitation" <?php echo ($target->carrier_type=='mobileCarrierLimitation')?'checked':''; ?> /> <?php echo 'Target specific Network Carrier';//$this->lang->line('label_target_specloc'); ?>

	<div id="carrieroperator">
		<ul id="networkcarriers">
		<?php if($geooperator_carrier): 
					foreach($geooperator_carrier as $geo_country):
					  $id=array(); $country=array();
					  $id	=explode(',',$target->carriers);
					  $country=explode(',',$target->network_country);
				?>
		<li <?php echo (in_array($geo_country->country,$country))?'style="color:#3E678E; font-weight:bold"':'style="color:#AAA;"'; ?>><span <?php echo (in_array($geo_country->country,$country))?'style="cursor:pointer;"':''; ?>><?php echo ucfirst($geo_country->country); ?></span>
		
		<?php $region_list = $this->mod_campaign->get_region_operator($geo_country->country);	
			if(!empty($region_list)):?>
			<ul>
				<?php foreach($region_list as $r): ?>
				<li><input type="checkbox" name="network_carriers[]" <?php echo (in_array($geo_country->country,$country) && in_array($r->id,$id))?'checked="checked"':''; ?> id="network_carriers" value="<?php echo $geo_country->country.':'.$r->id; ?>">&nbsp;<?php echo $r->carriername; ?></li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</li>
		<?php endforeach; 
				endif;
		?>
		</ul>
	</div>
</p>
<br/>
<!-- Mobile Network Carrier End -->

<h2><?php echo $this->lang->line('label_target_device_model'); ?></h2>
<p>
        <label><?php echo $this->lang->line('label_device_name'); ?></label>
        <select name="mobdevice">
                <option <?php echo ($target->model_type==0)?'selected':''; ?> value="0"><?php echo $this->lang->line('label_no_limit'); ?></option>
                <option <?php echo ($target->model_type=='==')?'selected':''; ?> value="=="><?php echo $this->lang->line('label_equal_to'); ?></option>
                <option <?php echo ($target->model_type=='!=')?'selected':''; ?> value="!="><?php echo $this->lang->line('label_different_from'); ?></option>
                <option <?php echo ($target->model_type=='=~')?'selected':''; ?> value="=~"><?php echo $this->lang->line('label_contains'); ?></option>
                <option <?php echo ($target->model_type=='!~')?'selected':''; ?> value="!~"><?php echo $this->lang->line('label_not_contain'); ?></option>
        </select>        
        <input type="text" name="device_name" value="<?php echo $target->model; ?>"/>
</p>
<!--
<h2><?php echo $this->lang->line('label_geography_operators'); ?></h2>
<br/>
<input type="radio" name="geooperatorcarrier" id="geooperatorcarrier" checked="checked" value="all" <?php echo ($target->operator_type=='all')?'checked':''; ?> onclick="hideGeoOperatcarrier()">
Target all geographic operators
<br><input name="geooperatorcarrier" id="geooperatorcarrier" type="radio"  value="specific" <?php echo ($target->operator_type=='specific')?'checked':''; ?> onclick="showGeoOperatcarrier()">
Target specific geographic operators

<div id="carrieroperator" style="width:250px; height:200px; overflow-y:scroll; border:1px solid #CCCCCC">

			  <table border="0" cellpadding="0" cellspacing="0">
			  <tbody>
			  <?php
				$i=0;
				$selcarrier = explode(',',$target->operators);
				foreach($geooperator_carrier as $carrier)
				{ 
				$country=$carrier->country;
				$id=$carrier->id;
				?>
				<tr>
			  <td width="" style="padding-left:5px !important;">
			  
				<?php  
				if(($i==0)||($temp_country!=$country))
				{
				echo '<strong>'.ucfirst($carrier->country).'</strong><br/>';
				}
				?>
				
				<input name="carrier[]" type="checkbox" value="<?php echo $carrier->id?>" <?php if(in_array($id,$selcarrier)){echo 'Checked';}?> >
				&nbsp;&nbsp;&nbsp;
				<?php echo ucfirst($carrier->carriername) ?>
				<?php 
				$temp_country=$carrier->country;
				$i++;?>
				</td>
				</tr>
				<?php }?>
			  
			  </tbody></table>
		  
		  </div>
<br/><br/><br/>
-->
<h2><?php echo $this->lang->line('label_target_demographics'); ?></h2>
<br/>
<div style="width:400px;height:200px;line-height:25px;">
        <div style="float:left;">
        <h4><?php echo $this->lang->line('label_target_gender'); ?></h4>
        <input type="radio" name="gender" id="allgender" value="all" <?php echo ($target->gender_type=='all')?'checked':''; ?> /> <?php echo $this->lang->line('label_target_all_user'); ?>
        <br/>
        <input type="radio" name="gender" id="male"  value="m" <?php echo ($target->gender_type=='m')?'checked':''; ?> /> <?php echo $this->lang->line('label_target_male_only'); ?>
        <br/>
        <input type="radio" name="gender" id="female"  value="f" <?php echo ($target->gender_type=='f')?'checked':''; ?> /> <?php echo $this->lang->line('label_target_female_only'); ?>
        <br/>
        </div>
        <div style="float:right;">
        <h4><?php echo $this->lang->line('label_target_age'); ?></h4>
        <input type="radio" name="agegroup" id="allage" onclick="hideAgeGroup();" value="all" <?php echo ($target->ages_type=='all')?'checked':''; ?>/> <?php echo $this->lang->line('label_target_ageall'); ?>
        <br/>
        <input type="radio" name="agegroup" id="specage" onclick="showAgeGroup();" value="specific" <?php echo ($target->ages_type=='specific')?'checked':''; ?>/> <?php echo $this->lang->line('label_target_agespec'); ?>
        <br/>
        <?php if(count($agegroup)>0): ?>
        <div id="targetagegroup">
            <?php
            $selage = explode(',',$target->ages);
            for($m=0;$m<count($agegroup);$m++):
                $totage = $agegroup[$m]->from.'-'.$agegroup[$m]->to;
                if(in_array($totage,$selage)): ?>
                <input type="checkbox" checked name="selage[]" id="selage<?php echo $totage; ?>" value="<?php echo $totage; ?>" /> <?php echo $totage; ?> &nbsp;&nbsp;<br/>
                <?php else: ?>
                <input type="checkbox" name="selage[]" id="selage<?php echo $totage; ?>" value="<?php echo $totage; ?>" /> <?php echo $totage; ?> &nbsp;&nbsp;<br/>
                <?php
                endif;
            endfor;
            ?>
        </div>
        <?php else: ?>
        <div id="targetagegroup">
        	<p><?php echo $this->lang->line('label_sorry_records_not_found'); ?></p>
        </div>
        <?php endif; ?>
        </div>
</div>



<br/><br/>
<div>
<button><?php echo $this->lang->line('label_update_targeting'); ?></button>
<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
</div>
</fieldset>
</div><!--form-->
</form>

<div id="tooltip" style="display: none;" ><h3 style="display: none;"><?php echo $this->lang->line('label_target_tooltip');?></h3></div>
<script>
			/*
			* jQuery easing functions (for this demo)
			*/
			jQuery.extend( jQuery.easing,{
				def: 'easeOutQuad',
				swing: function (x, t, b, c, d) {
					//alert(jQuery.easing.default);
					return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
				},
				easeInQuad: function (x, t, b, c, d) {
					return c*(t/=d)*t + b;
				},
				easeOutQuad: function (x, t, b, c, d) {
					return -c *(t/=d)*(t-2) + b;
				},
				easeInOutQuad: function (x, t, b, c, d) {
					if ((t/=d/2) < 1) return c/2*t*t + b;
					return -c/2 * ((--t)*(t-2) - 1) + b;
				},
				easeOutElastic: function (x, t, b, c, d) {
					var s=1.70158;var p=0;var a=c;
					if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
					if (a < Math.abs(c)) { a=c; var s=p/4; }
					else var s = p/(2*Math.PI) * Math.asin (c/a);
					return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
				},
				easeInOutElastic: function (x, t, b, c, d) {
					var s=1.70158;var p=0;var a=c;
					if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
					if (a < Math.abs(c)) { a=c; var s=p/4; }
					else var s = p/(2*Math.PI) * Math.asin (c/a);
					if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
					return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
				},
				easeInBack: function (x, t, b, c, d, s) {
					if (s == undefined) s = 1.70158;
					return c*(t/=d)*t*((s+1)*t - s) + b;
				},
				easeOutBack: function (x, t, b, c, d, s) {
					if (s == undefined) s = 1.70158;
					return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
				},
				easeInOutBack: function (x, t, b, c, d, s) {
					if (s == undefined) s = 1.70158; 
					if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
					return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
				}
			});
			
		
	</script>
<script type="text/javascript">
//Call dropdowncontent.init("anchorID", "positionString", glideduration, "revealBehavior") at the end of the page:
dropdowncontent.init("searchlink", "right-top", 500, 'mouseover');
dropdowncontent.init("searchlink1", "left-top", 500, 'mouseover');
dropdowncontent.init("searchlink2", "right-top", 500, 'mouseover');
dropdowncontent.init("searchlink3", "right-top", 500, 'mouseover');
dropdowncontent.init("searchlink4", "right-top", 500, 'mouseover');


<?php if($target->network_country!=''):?>

		window.onload = get_network('<?php echo $target->network_country; ?>');
		
<?php endif;?>

function get_network(country)
{	
	if(country)
	{	
		jQuery.ajax({
			type: "POST",
			data: { country_code: country,selected_country_code:'<?php echo $target->network_country; ?>',selected_carriers:'<?php echo $target->carriers; ?>'},
			url: '<?php echo site_url('admin/inventory_campaigns/region'); ?>',
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

jQuery(document).ready(function(){
		// first example
	jQuery("#networkcarriers").treeview({
		collapsed: true,
		persist: "location"
	});
		
		
	});	
</script>
