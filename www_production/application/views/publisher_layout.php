<?php
/* Header */ 
$this->load->view("includes/publisher/header");

/* Menu */ 
$this->load->view("includes/publisher/menu");

/* Account */ 
$this->load->view("includes/publisher/account");

/* Sidebar */ 
$this->load->view("includes/publisher/sidebar");

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
$this->load->view("includes/publisher/footer");
?>
