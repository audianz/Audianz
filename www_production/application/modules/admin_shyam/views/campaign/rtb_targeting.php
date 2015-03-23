<script type="text/javascript">
jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form_mobile_targeting").validationEngine({promptPosition : "topLeft"});

       
        jQuery("div[id='deviceloc']").slideUp("slow");
       
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

<h1 class="pageTitle"><?php echo $this->lang->line('label_targeting_title'); ?></h1>
<form name="form_mobile_targeting" id="form_mobile_targeting" action="<?php echo site_url('admin/inventory_campaigns/rtb_targeting_limitation_process'); ?>" method="post" onsubmit="return check_filled_data();">
<input type="hidden" name="campaign" value="<?php echo $sel_camp; ?>">
<div class="form_default" style="line-height: 25px;">
<fieldset>

<h2><?php echo $this->lang->line('label_geography_operators'); ?></h2>
<p>
    <input type="radio" name="geolocation" id="locatall" checked onclick="hideGeoLoc();" value="geographic_all" /> <?php echo $this->lang->line('label_target_allloc'); ?>
    <br/>
    <input type="radio" name="geolocation" id="locatselect" onclick="showGeoLoc();" value="geographic_locations" /> <?php echo $this->lang->line('label_target_specloc'); ?>
    <div id="deviceloc">
    <?php if(count($geolocation)>0): ?>
    <table width="40%">
            <tr>
                <td align="center">
                    <select name="source_loc" id="source_loc" multiple="multiple" size="<?php echo (count($geolocation)>=20)?'20':count($geolocation); ?>">
                        <optgroup label="<?php echo $this->lang->line('label_box_tot_loc'); ?>"></optgroup>
                        <?php foreach($geolocation as $location): ?>
                            <option value="<?php echo $location->code; ?>"><?php echo $location->name; ?></option>
                        <?php endforeach; ?>
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
                            </select>
                    </td>
            </tr>
    </table>
    	<?php else: ?>
    	<br/>
    	<div class="notification msgalert" style="width:400px;height:30px;">
    		<?php echo $this->lang->line('label_no_loc'); ?> <a href="<?php echo site_url('admin/settings_geo_locations/add_geo_location'); ?>"><?php echo $this->lang->line('label_click_add_loc'); ?></a>
    	</div>
	<?php endif; ?> 
    </div>
</p>

<div>
<button><?php echo $this->lang->line('label_update_targeting'); ?></button>
<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
</div>
</fieldset>
</div><!--form-->
</form>
