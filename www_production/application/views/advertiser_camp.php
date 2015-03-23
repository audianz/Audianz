<?php
/* Header */ 
$this->load->view("includes/advertiser/header");

/* Menu */ 
$this->load->view("includes/advertiser/menu");

/* Account */ 
$this->load->view("includes/advertiser/account");

/* Sidebar */ 
$this->load->view("includes/advertiser/option");

?>

<div class="maincontent">
	
    <div class="breadcrumbs">
    	<?php echo $breadcrumb; ?>
    </div><!-- breadcrumbs -->

    <div class="left">
    
    	<?php echo $page_content; ?>
    
        <!-- Your Working location -->
		
    </div><!--fullpage-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<br />
<?php
/* Footer */ 
$this->load->view("includes/advertiser/footer");
?>
