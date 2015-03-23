		<h3><?php echo $this->lang->line("label_inventory_zone_banners_title");?></h3>
		<br/>
		<select>
			<option> Link Banners By Parent Campaigns</option>
			<option> Link Individual Banners</option>
		</select>
    
		<br/><br/>
	
        <table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
            	<col class="head0" width="3%" />
                <col class="head1" width="25%" />
		<col class="head0" width="25%" />
		</colgroup>
            <tr>
            	<td align="center"><input type="checkbox" class="checkall" /></td>
                <td><?php echo $this->lang->line("label_inventory_zone_banners_advertiser");?></td>
                <td><?php echo $this->lang->line("label_inventory_zone_banners_campiagn");?></td>
            </tr>
        </table>
        
        <div class="sTableWrapper" width="100%">
            <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
                <colgroup>
                    <col class="con0" width="3%" />
                    <col class="con1" width="25%" />
                    <col class="con0" width="25%" />
                </colgroup>
                <tr>
                    <td align="center"><input type="checkbox"  /></td>
                    <td>newuser</td>
                    <td>	democampaign</td>
                </tr>
				<tr>
                    <td align="center"><input type="checkbox"  /></td>
                    <td>advertiser</td>
                    <td>	new campaign </td>
                </tr>
				<tr>
                    <td align="center"><input type="checkbox"  /></td>
                    <td>advertiser</td>
                    <td> Test Camapign Copy	</td>
                </tr>
				<tr>
                    <td align="center"><input type="checkbox"  /></td>
                    <td>advertiser</td>
                    <td> first	</td>
                </tr>
		    </table>
	     </div><!--sTableWrapper-->
		  <a style="float:left;margin-left:0px;margin-top:10px;" href='#' class="addNewButton"><?php echo $this->lang->line("label_inventory_zone_banners_save/update");?></a>
		<br/><br/><br/>
        <table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
            	<col class="head0" width="20%" />
                <col class="head1" width="25%" />
				<col class="head0" width="25%" />
			</colgroup>
            <tr>
                <td><?php echo $this->lang->line("label_inventory_zone_banners_campaign");?></td>
				<td><?php echo $this->lang->line("label_inventory_zone_banners_banner");?></td>
                <td><?php echo $this->lang->line("label_inventory_zone_banners_showbanner");?></td>
            </tr>
        </table>
        <div class="sTableWrapper" width="100%">
            <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
                <colgroup>
                    <col class="con0" width="20%" />
                    <col class="con1" width="25%" />
                    <col class="con0" width="25%" />
                </colgroup>
                <tr>
                    <td>Testcampaign</td>
                    <td>banner</td>
                    <td>Show Banner</td>
                </tr>
		    </table>
	     </div><!--sTableWrapper-->