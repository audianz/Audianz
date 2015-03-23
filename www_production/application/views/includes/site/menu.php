<?php 
$cur_page = explode("_",$this->uri->segment(3)); 
$pagename = explode('-',$cur_page[0]);

if(empty($pagename[0])):
$pagename[0] = '0';
endif;
?>
<div class="tabmenu">		
<?php 
if(count($menu_list) > 0 ){
?>
	<ul>
		<?php
		asort($menu_list);
		$i=1;	
		foreach($menu_list as $parent_menu){
		$child	=	$parent_menu['child'];		
		$chilsel_parent = $this->mod_site->get_child_parent($pagename[0]);
		
		switch($i)
		{
			case 1:
				$menuicon = "class='dashboard'";
			break;
			case 2:
				$menuicon = "class='elements'";
			break;
			case 3:
				$menuicon = "class='reports'";
			break;
			case 4:
				$menuicon = "class='users'";
			break;
			default:
				$menuicon = "class='dashboard'";
			break;
		}
		//echo $pagename[0]."==".$parent_menu['page_id']."::".$chilsel_parent."==".$parent_menu['page_id'];
		?>
		<li <?php echo($pagename[0]==$parent_menu['page_id'] || $chilsel_parent==$parent_menu['page_id'])?"class='current'":""; ?>><a href="<?php echo ($parent_menu['page_id'] > 0 AND $parent_menu['page_id'] != '')?site_url("site/view/".$parent_menu['page_id'].'-'.strtolower($parent_menu['name'])):"#"; ?>" <?php echo $menuicon; ?> class="users" alt="<?php echo ucfirst($parent_menu['name']); ?>"><span><?php echo $parent_menu['name']; ?></span></a>
			<?php
			if(count($child) > 0 AND $child != ''){
			asort($child);
			?>
			<ul class="subnav">
			<?php foreach($child as $child_menu)
			{ ?>
				<li <?php echo($pagename[0]==$child_menu['id'])?"class='current'":""; ?>><a href="<?php echo ($child_menu['page_id'] > 0 AND $child_menu['page_id'] != '')?site_url("site/view/".$child_menu['page_id'].'-'.strtolower($child_menu['name'])):"#"; ?>" alt="<?php echo ucfirst($child_menu['name']); ?>"><span><?php echo $child_menu['name']; ?></span></a></li>
			<?php } ?>
			</ul>
		<?php } ?>								
		</li>		
		<?php 
		$i++;
		} ?>
	</ul>
<?php } ?>	
</div><!-- tabmenu -->	

