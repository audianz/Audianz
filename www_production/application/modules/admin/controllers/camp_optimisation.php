<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Camp_optimisation extends CI_Controller {
	/* Page Limit */
	var $page_limit = 10;
	var $status     = "all";

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('mod_optimisation');
		
	}

	/* Campaigns Page */
	public function index()
	{
		$this->get_optimisation_mix();
	}
	
	/* To get Networks optimization list */
	public function get_optimisation_mix()
	{
		/*-------------------------------------------------------------
		 Breadcrumb Setup Start
		-------------------------------------------------------------*/
		$link = breadcrumb();
		$data['breadcrumb'] = $link;
		$data['list']=$this->mod_optimisation->camp_optimization_list();
		$data['page_content']	= $this->load->view("admin/campaign/manage_optimisation",$data,true);
		$this->load->view('page_layout',$data);
	}
	
	/* To edit the network mix */
	public function edit_network_mix($id)
	{ 
		$link = breadcrumb();
		$data['breadcrumb'] = $link;
		$data['list']=$this->mod_optimisation->camp_optimization_list();
		$data['network']=$this->mod_optimisation->get_network_data($id);
		$data['page_content']	= $this->load->view("admin/campaign/edit_network_mix",$data,true);
		$this->load->view('page_layout',$data);
	}
	
	/*To perform edit action to the network  Information */
	public function network_edit_process()
	{
		$network=$_POST;
		$edit=$this->mod_optimisation->edit_network($network);
		if($edit==1)
		{ 
			$this->session->set_flashdata('message',"Network Optimisation details edited successfully" );
			redirect('admin/camp_optimisation');
		}
	}
	
	/* To add new network to the optimisation table */
	public function add_network()
	{
		$link = breadcrumb();
		$data['breadcrumb'] = $link;
		$data['list']=$this->mod_optimisation->camp_optimization_list();
		$data['page_content']	= $this->load->view("admin/campaign/add_network",$data,true);
		$this->load->view('page_layout',$data);
	}
	
	/* Inserting new network  */
	public function network_add_process()
	{
		$network=$_POST;
		$add=$this->mod_optimisation->add_network($network);
		if($add==1)
		{ 
			$this->session->set_flashdata('message',"New Network has been added successfully" );
			redirect('admin/camp_optimisation');
		}
	}
	
	/* To remove network from existing list */
	public function remove_network()
	{
		$networks= $_POST['arr'];
		if($networks[0]=='checkall')
		{
			$val = array_shift($networks);
		}
		$count=count($networks);
		for($m=0;$m<$count;$m++)
		{ 
			$id=$networks[$m];
			$this->mod_optimisation->remove_network($id);
		}
	}
		
}
	
?>
