<div style="padding:1px;margin:10px 10px 9px;width:97%;border:1px solid #637CAE;min-height:50px;background-color:#55555;">
		<table cellpadding="0" cellspacing="0" class="childTable" id="userlist" width="100%">
            <thead>
                <tr>
                    <th align="left" style="text-align:left !important" class="child0" width="25%"><?php echo $this->lang->line('lang_statistics_publisher_zone_name');?></th>
                  	 <th align="center" class="child0" width="10%"><?php echo $this->lang->line('lang_statistics_publisher_impression');?></th>
                    <th align="center" class="child0" width="10%"><?php echo $this->lang->line('lang_statistics_publisher_clicks');?></th>
                    <th align="center" class="child0" width="10%"><?php echo $this->lang->line('lang_statistics_publisher_conversions');?></th>
                    <th align="center" class="child0" width="10%"><?php echo $this->lang->line('lang_statistics_publisher_call');?></th>
                    <th align="center" class="child0" width="10%"><?php echo $this->lang->line('lang_statistics_publisher_web');?></th>
                    <th align="center" class="child0" width="10%"><?php echo $this->lang->line('lang_statistics_publisher_map');?></th>
					<th align="center" class="child0" width="10%"><?php echo $this->lang->line('lang_statistics_publisher_ctr');?></th>
					<th align="center" class="child0" width="10%"><?php echo $this->lang->line('lang_statistics_publisher_revenue');?></th>
					<th align="center" class="child0" width="10%"><?php echo $this->lang->line('lang_statistics_publisher_share');?></th>
                </tr>
            </thead>
			<colgroup>
				<col class="con0" />
				<col class="con1" />
				<col class="con0" />
				<col class="con1" />
				<col class="con0" />
				<col class="con1" />
				<col class="con0" />
				<col class="con1" />
				<col class="con0" />
				<col class="con1" />
			</colgroup>
            <tbody>
               <?php
			   
			   		if(count($publisher_list > 0)){
						
						foreach($publisher_list as $data){
								
								?>
								<tr style="font-weight:bold;">
								<td align="left" style="text-align:left" >
										<?php if( $data['IMP'] != 0 || $data['CLK'] != 0 || $data['CON'] != 0): ?>
											<a href="javascript: view_reports_date_wise('ZONE','<?php echo $data['accountid']; ?>','<?php echo $data['zoneid']; ?>')" ><?php echo view_text($data['zonename']); ?></a>
										<?php else: ?>
											<?php echo view_text($data['zonename']); ?>
										<?php endif; ?>
									</td>
									
									<td><?php echo $data['IMP'] ?></td>
									<td><?php echo $data['CLK'] ?></td>
									<td><?php echo $data['CON'] ?></td>
									<td><?php echo $data['CALL'] ?></td>
									<td><?php echo $data['WEB'] ?></td>
									<td><?php echo $data['MAP'] ?></td>
									<td><?php echo $data['CTR'] ?> %</td>
									<td>$ <?php echo $data['SPEND'] ?></td>
									<td>$ <?php echo $data['PUBSHARE'] ?></td>
								</tr>
						<?php
						}
					}
			   ?>			   
            </tbody>
        </table>
</div>

