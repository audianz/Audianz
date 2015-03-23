<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

	//public $CI;
	public function __construct($config= array())
	{
		parent::__construct($config);

		//log_message('debug', '*** Hello from MY_Form_validation ***');
	}


	/*function MY_Form_validation($config = array())
	{
			parent::CI_Form_validation($config);
		
	}*/
	
	public function exist($str, $value){       

	  $CI =& get_instance();
	  list($table, $column) = explode('.', $value, 2); 
	  
	  $query = $CI->db->query("SELECT COUNT(*) AS count FROM $table WHERE $column = '".$str."'");
	  
	  $row = $query->row();
	
	 //return ($row->count > 0) ? TRUE : FALSE;
		
		if($row->count>0)
		{
			$query = $CI->db->query("SELECT COUNT(*) AS count FROM $table WHERE $column = '".$str."' AND status =1");
			$row_valid = $query->row();
			if($row_valid->count >0)
			{
				return TRUE;
			}else{ 
				$CI->form_validation->set_message('exist','Sorry,the Entered %s has been expired');
				return FALSE;
			}
		}else{
		
			$CI->form_validation->set_message('exist','Invalid %s has been entered');
			return FALSE;
		}
	

	}
	
		//Checks alpha numeric with dash and space
	function alpha_numeric_dash_space($str)
    	{
		$this->CI->form_validation->set_message('alpha_numeric_dash_space', 'The %s must be a valid Characters.');    	    
		return ( ! preg_match("/^([-a-z0-9_ ])+$/i", $str)) ? FALSE : TRUE;
    	}
    	
    //Checks alpha numeric with dash,space and '
	function alpha_dash_space_quotes($str)
    	{
		$this->CI->form_validation->set_message('alpha_dash_space_quotes', 'Please enter the valid %s.');    	    
		return ( ! preg_match("/^([-a-z_ '])+$/i", $str)) ? FALSE : TRUE;
    	}
    		
    //Checks for alpha_numeric with basic symbols
    function alpha_dash_space_symbols($str)
    {
		$this->CI->form_validation->set_message('alpha_dash_space_symbols', 'Please enter the valid %s.');    	    
		return ( ! preg_match("/^([-a-z_ '\/()\"])+$/i", $str)) ? FALSE : TRUE;
    }
    
    	
    	
   //Password Exist or not
   function password_check($str,$value)
   {
	   $str = md5($str);
	   
	   $CI =& get_instance();
	   list($table, $column, $column2, $id) = explode('.', $value, 4); 
	  
	   $query = $CI->db->query("SELECT COUNT(*) AS count FROM $table WHERE $column = '".$str."' AND $column2 = $id");
	 	
	  $row = $query->row();
		
	   if($row->count>0)
	   {
			return TRUE;		
	   }else{
			$CI->form_validation->set_message('password_check','Sorry,Please type the old password correctly or check whether caps is on or not');
			return FALSE;
	   }	
	}
    	 
}
