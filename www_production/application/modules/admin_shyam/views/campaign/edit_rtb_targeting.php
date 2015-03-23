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

       var updateloc = jQuery('input[name=geolocation]:checked', '#form_mobile_targeting').val();
        if(updateloc=='geographic_all'){
            hideGeoLoc();
        } else {
            showGeoLoc();
        }

    <?php if(!empty($target->data)): ?>
			jQuery("div[id='deviceloc']").slideDown("slow");
	
<?php endif; ?>    
		
});



function showGeoLoc(){
    jQuery("div[id='deviceloc']").slideDown("slow");
}

function hideGeoLoc(){
    jQuery("div[id='deviceloc']").slideUp("slow");
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
    document.location.href='<?php echo site_url("admin/inventory_campaigns"); ?>';
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
<form name="form_mobile_targeting" id="form_mobile_targeting" action="<?php echo site_url('admin/inventory_campaigns/rtb_targeting_limitation_process'); ?>" method="post" onsubmit="return check_filled_data();">
     <input type="hidden" name="campaign" id="campaign" value="<?php echo $sel_camp; ?>" />
<div class="form_default" style="line-height: 25px;position:relative">
<div id="menu" class="menu" style="width: 250px;">
				
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
					<td colspan="2"><span class="scroll_subtitile"><?php echo $this->lang->line('label_mobile_geo_ope');?></span></td>
				</tr>
				  
				  <tr>
                  <td><?php echo $this->lang->line('label_geographic_locations');?></td>
				  <td>
							<?php if(!empty($target->data)):
									?>
									<b><a  href="#"  id="searchlink3" rel="subcontent3"><?php echo $this->lang->line('label_geographic_loc_list');?><span class="popup_icon"></span></a></b>
									<div id="subcontent3" class="popup_box"> 
									<h4><?php echo $this->lang->line('label_geographic_locations');?></h4>
									<div class="list_data">
								<?php			$gloc = explode(',',$target->data);
											for($i=0;$i<count($geolocation);$i++):
												if(in_array($geolocation[$i]->code,$gloc)):
												echo	$geolocation[$i]->name;
												echo '<br/> ';
												endif;
											endfor; ?>
											</div>
										</div>
							<?php		
								else:
									echo $this->lang->line('label_all_locations');
								?>
									
						<?php	endif;
							?>
					 </td>
                  </tr>
			 </tbody>
        </table>
		
</div>

<fieldset>

<h2><?php echo $this->lang->line('label_geography_operators'); ?></h2>
<p>
    <input type="radio" name="geolocation" id="locatall" onclick="hideGeoLoc();" value="geographic_all" <?php echo (!empty($target->data))?'':'checked'; ?>  /> <?php echo $this->lang->line('label_target_allloc'); ?>
    <br/>
    <input type="radio" name="geolocation" id="locatselect" onclick="showGeoLoc();" value="geographic_locations" <?php echo (!empty($target->data))?'checked':''; ?> /> <?php echo $this->lang->line('label_target_specloc'); ?>
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
                                <?php $gloc = explode(',',$target->data);
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
</script>
