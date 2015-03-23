<?php 
$cur_page = explode("_",$this->uri->segment(3)); 
$pagename = explode('-',$cur_page[0]);

if(empty($pagename[0])):
$pagename[0] = '0';
endif;
?>
<div class="sidebar">
	<div id="accordion">
        <h3 class="open">Menu</h3>
        <div class="content" style="display: block;">
        <?php 
        if(count($menu_list) > 0 ){ 
			asort($menu_list);
			$i=1;
			foreach($menu_list as $parent_menu){
			$child	=	$parent_menu['child'];		
			$chilsel_parent = $this->mod_site->get_child_parent($pagename[0]);
			switch($i)
			{
				case 1:
					$menuicon = "class='home'";
				break;
				case 2:
					$menuicon = "class='form'";
				break;
				case 3:
					$menuicon = "class='table'";
				break;
				case 4:
					$menuicon = "class='grid'";
				break;
				case 5:
					$menuicon = "class='calendar'";
				break;
				case 6:
					$menuicon = "class='buttons'";
				break;
				case 7:
					$menuicon = "class='file'";
				break;				
				default:
					$menuicon = "class='gallery'";
				break;
			}
			?>
			<ul class="leftmenu" style="margin-left:-15px;margin-right:-15px;">
			<li <?php echo ($pagename[0]==$parent_menu['page_id'] || $chilsel_parent==$parent_menu['page_id'])?"class='current'":""; ?>><a <?php echo $menuicon; ?> href="<?php echo ($parent_menu['page_id'] > 0 AND $parent_menu['page_id'] != '')?site_url("site/view/".$parent_menu['page_id'].'-'.strtolower($parent_menu['name'])):"#"; ?>"><?php echo $parent_menu['name']; ?></a></li>			
			</ul>
        <?php
			$i++;
			}
        } ?>
        </div>
        
	</div>

</div><!-- leftmenu -->
