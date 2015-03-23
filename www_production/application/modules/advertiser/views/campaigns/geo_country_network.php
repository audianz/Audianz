<td align="center">
    <select name="source_mobi" id="source_mobi" multiple="multiple" size="<?php echo (count($region)>=20)?'20':count($region); ?>" class="sf">
        <optgroup label="<?php echo 'List of Network';//$this->lang->line('label_box_tot_loc'); ?>"></optgroup>
        <?php foreach($region as $country_region): 
			if(isset($selected))
						{
							$dos = explode(',',$selected);
							if(!in_array($country_region->id,$dos))
							{
				?>
								<option value="<?php echo $country_region->id; ?>"><?php echo $country_region->carriername; ?></option>
				<?php 		} 
						}
						else
						{ 
				?>
							<option value="<?php echo $country_region->id; ?>"><?php echo $country_region->carriername; ?></option>
				<?php 		
						}
					?>	
          <?php endforeach; ?>
    </select>                    
</td>
<td align="center" style="vertical-align:middle;padding-right: 5px;">
            <input type="Button" value="Add >>" style="width:100px" onClick="SelectMoveRows(document.form_mobile_targeting.source_mobi,document.form_mobile_targeting.destination_mobi,1)"><br>
            <br>
            <input type="Button" value="<< Remove" style="width:100px" onClick="SelectMoveRows(document.form_mobile_targeting.destination_mobi,document.form_mobile_targeting.source_mobi)">
</td>
<td align="center">
            <select name="destination_mobi[]" id="destination_mobi" multiple="multiple" class="validate[required] sf" alt="<?php echo 'Selected Network';//$this->lang->line('label_select_specific_geo_locations'); ?>" size="<?php echo (count($region)>=20)?'20':count($region); ?>">
                <optgroup label="<?php echo 'Selected Network';//$this->lang->line('label_box_sel_loc'); ?>"></optgroup>
                <?php  
					if(isset($selected))
					{ 
						$dos = explode(',',$selected);
						for($i=0;$i<count($region);$i++)
						{
							if(in_array($region[$i]->id,$dos))
							{
				?>
								<option value="<?php echo $region[$i]->id; ?>" selected="selected"><?php echo $region[$i]->carriername; ?></option>
				<?php 		}
						}
					}
									
				?>
            </select>
</td>

<script type="text/javascript">
/*function SelectMoveRows(SS1,SS2,flag)
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
}*/
</script>
