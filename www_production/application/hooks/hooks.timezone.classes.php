<?php
/*
| -------------------------------------------------------------------------
| Hooks Classes
| -------------------------------------------------------------------------
| This file lets you define "hooks classes - Set_adnetwork_timezone" to set the dynamic timezone according to the package
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
class Set_adnetwork_timezone
{
	function Fetch_adnetwork_timezone()
	{
		$CI = &get_instance();
		$CI->load->database();
		
		$query = $CI->db->select('value')->get_where('ox_account_preference_assoc',array('preference_id'=>'16','account_id'=>'1'));
		
		if($query->num_rows() >0)
		{
				
			$time_zone = $query->row();
			
			$tz_check_query = $CI->db->get_where('oxm_timezone',array('timezone'=>$time_zone->value));
			
			if($tz_check_query->num_rows() > 0){
			
				// OVERWITE PHP DATE FUNCTIONS BASED ON AD NETWORK TIME ZONE VALUE		
				date_default_timezone_set($time_zone->value);
			 
				$objDT = new DateTime();
				$offset = $objDT->getOffset(); 
				
				$offsetHours = floor(abs($offset) / 3600);
				$offsetMinutes = floor((abs($offset) - ($offsetHours * 3600)) / 60);
				$offsetString  = ($offset < 0 ? '-' : '+');
				$offsetString .= (strlen($offsetHours) < 2 ? '0' : '').$offsetHours;
				$offsetString .= ':';
				$offsetString .= (strlen($offsetMinutes) < 2 ? '0' : '').$offsetMinutes;
				
				// OVERWITE MYSQL DATE FUNCTIONS BASED ON AD NETWORK TIME ZONE VALUE			
				
				$CI->db->query("SET time_zone='{$offsetString}'");
			
			}
			else
			{
				return FALSE;
			}
			
			
						
		}else{
			return FALSE;
		}
	}
}
/* End of file hooks.classes.php */
/* Location: ./application/hooks/hooks.classes.php */
