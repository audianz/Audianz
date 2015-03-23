<?php
/* Header */ 
$this->load->view("includes/site/header");

/* Menu */ 
$this->load->view("includes/site/menu");

/* Account */ 
//$this->load->view("includes/site/account");
?>
</div><!-- header -->
<?php
/* Sidebar */ 
$this->load->view("includes/site/sidebar");

?>

<div class="maincontent">
	
    	<?php echo $page_content; ?>
    
</div><!--maincontent-->

<br />
<?php
/* Footer */ 
$this->load->view("includes/site/footer");
?>
