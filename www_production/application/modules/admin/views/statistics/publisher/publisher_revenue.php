<div style="padding:1px;margin:10px 10px 9px;width:97%;border:1px solid #637CAE;min-height:50px;background-color:#55555;">
		<table cellpadding="0" cellspacing="0" class="childTable" id="userlist" width="100%">
            <thead>
                <tr>
                    <th align="left" style="text-align:left !important" class="child0" width="25%"><?php echo $this->lang->line('label_payment_history_month');?></th>
                  	<th align="center" class="child0" width="10%"><?php echo $this->lang->line('label_payment_history_revenue');?></th>
                    	<th align="center" class="child0" width="10%"><?php echo $this->lang->line('label_payment_history_share');?></th>
			<th align="center" class="child0" width="10%"><?php echo $this->lang->line('label_payment_history_Paid');?></th>
			<th align="center" class="child0" width="10%"><?php echo $this->lang->line('label_payment_history_Unpaid');?></th>			
                </tr>
            </thead>
			<colgroup>
				<col class="con0"/>
				<col class="con1"/>
				<col class="con0"/>
				<col class="con1"/>
				<col class="con0"/>							
			</colgroup>
            <tbody>
            
            <?php if(count($publisher_list)>0): 
	    		$monthly_list = $publisher_list['stat_list'];
	    		if(!empty($monthly_list)):
	    		foreach($monthly_list as $key => $data):
	    		?>	
				<tr style="font-weight:bold;">
				<td align="left" style="text-align:left"><?php echo date("F", mktime(0, 0, 0, $key)).' '.$data['YEAR']; ?></td>									
				<td><?php echo $data['SPEND']; ?></td>
				<td><?php echo $data['PUBSHARE']; ?></td>
				<td><?php echo $data['PAID']; ?></td>
				<td><?php echo number_format($data['PUBSHARE']-$data['PAID'],2); ?></td>				
				</tr>				
				
	    <?php endforeach; 	    		
		    else:
	    	    ?>
	    	    <tr style="font-weight:bold;">
	    	    	<td colspan="5">Sorry! No Records Found</td>
	    	    </tr>
	    <?php endif;
	    endif; ?>					
            </tbody>
        </table>
</div>

