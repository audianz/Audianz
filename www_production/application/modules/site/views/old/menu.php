		<?php 
			if(count($menu_list) > 0 ){
				?>
				<ul>
				<?php
				asort($menu_list);
				foreach($menu_list as $parent_menu){
					
					$child	=	$parent_menu['child'];
					?>
								<li><a href="<?php echo ($parent_menu['page_id'] > 0 AND $parent_menu['page_id'] != '')?site_url("page/view/".$parent_menu['page_id'].""):"#"; ?>" class="elements"><span><?php echo $parent_menu['name']; ?></span></a>
								<?php
									if(count($child) > 0 AND $child != ''){
									 asort($child);
									?>
									<ul class="subnav">
									<?php
										foreach($child as $child_menu){
									?>
										<li><a href="<?php echo ($parent_menu['page_id'] > 0 AND $parent_menu['page_id'] != '')?site_url("page/view/".$parent_menu['page_id'].""):"#"; ?>"><span><?php echo $child_menu['name']; ?></span></a></li>
									<?php		
										}	
									?>
									</ul>
									<?php							
									}
								?>								
								</li>		
					<?php
					
				}
				?>
				</ul>
		<?php
			}
		?>		