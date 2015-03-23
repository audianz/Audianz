<div style="padding:1px;margin:10px 10px 9px;width:97%;border:1px solid #637CAE;min-height:100px;background-color:#55555;">
		<table cellpadding="0" cellspacing="0" class="childTable" id="userlist" width="100%">
            <thead>
                <tr>
                    <th align="left" style="text-align:left !important" class="child0" width="25%"><?php echo $this->lang->line('lang_statistics_advertiser_campaigns');?></th>
                    <th align="left" style="text-align:left !important"  class="child0" width="25%"><?php echo $this->lang->line('lang_statistics_advertiser_banners');?></th>
                    <th align="center" class="child0" width="10%"><?php echo $this->lang->line('lang_statistics_advertiser_impression');?></th>
                    <th align="center" class="child0" width="10%"><?php echo $this->lang->line('lang_statistics_advertiser_clicks');?></th>
                    <th align="center" class="child0" width="10%"><?php echo $this->lang->line('lang_statistics_advertiser_conversions');?></th>
					<th align="center" class="child0" width="10%"><?php echo $this->lang->line('lang_statistics_advertiser_ctr');?></th>
					<th align="center" class="child0" width="10%"><?php echo $this->lang->line('lang_statistics_advertiser_spent');?></th>
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
			</colgroup>
            <tbody>
               <?php
			   		if(count($campaigns_list['campaigns'])){
						//print_r($reports['reports_campaigns']);
						foreach($campaigns_list['campaigns'] as $campID => $campaignname){
								$camp_report = $reports['reports_campaigns']; 
								?>
								<tr style="font-weight:bold;">
									<td align="left" style="text-align:left" >
										<?php if($camp_report[$campID]['IMP'] != 0 || $camp_report[$campID]['CLK'] != 0 || $camp_report[$campID]['CON'] != 0): ?>
											<a href="javascript: view_reports_date_wise('CAMP','<?php echo $campID; ?>')" ><?php echo view_text($campaignname); ?></a>
										<?php else: ?>
											<?php echo view_text($campaignname); ?>
										<?php endif; ?>
									</td>
									<td>&nbsp;</td>
									<td><?php echo $camp_report[$campID]['IMP'] ?></td>
									<td><?php echo $camp_report[$campID]['CLK'] ?></td>
									<td><?php echo $camp_report[$campID]['CON'] ?></td>
									<td><?php echo $camp_report[$campID]['CTR'] ?> %</td>
									<td>$ <?php echo $camp_report[$campID]['SPEND'] ?></td>
								</tr>
								<?php 
									if(isset($campaigns_list['banners'][$campID]) AND count($campaigns_list['banners'][$campID])){
									
										foreach($campaigns_list['banners'][$campID] as $bannerData){
											$banner_report = $reports['reports_banners']; 	
										?>
											<tr>
												<td>&nbsp;</td>
												<td align="left" style="text-align:left" >
												<?php if($banner_report[$campID][$bannerData['bannerid']]['IMP'] != 0 || $banner_report[$campID][$bannerData['bannerid']]['CLK'] != 0 || $banner_report[$campID][$bannerData['bannerid']]['CON'] != 0): ?>
													<a href="javascript: view_reports_date_wise('BAN','<?php echo $bannerData['bannerid']; ?>')" ><?php echo view_text($bannerData['description']); ?></a>
												<?php else: ?>
													<?php echo view_text($bannerData['description']); ?>
												<?php endif; ?>
												<td><?php echo $banner_report[$campID][$bannerData['bannerid']]['IMP'] ?></td>
												<td><?php echo $banner_report[$campID][$bannerData['bannerid']]['CLK'] ?></td>
												<td><?php echo $banner_report[$campID][$bannerData['bannerid']]['CON'] ?></td>
												<?php
									if($banner_report[$campID][$bannerData['bannerid']]['CTR'] == 0)
									{
									?>
									<td><?php echo "0.00" ?> %</td>
									<?php									
									}
									else
									{
									?>
									<td><?php echo $banner_report[$campID][$bannerData['bannerid']]['CTR'] ?> %</td>
									<?php
									}
									
									?>			
									<td>$ <?php echo $banner_report[$campID][$bannerData['bannerid']]['SPEND'] ?></td>
											</tr>
										<?php
										}
									}
								?>								
							<?php
						}
					}
			   ?>			   
            </tbody>
        </table>
</div>
