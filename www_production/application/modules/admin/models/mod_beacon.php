<?php
/**
 *
 * @author soumya
 * This class is used to perfrom common database operations adding, updating,deleting and fetching beacon data
 */
class Mod_beacon extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();

	}
	
	
	/* To enter beacon master setup details */
	
	function store_setup_data($data)
	{
		$sql="INSERT INTO `AZ_BEACON_MASTER` ( `Beacon_Lot_No`, `Invoice_Details`, `Date_of_Invoice`, `Vendor_Name`, `Vendor_Code`, `No_of_beacon`, `Beacon_Series`,`UUID`) 
		VALUES ('".$data[beacon_lot]."','".$data[invoice]."', '".$data[invoice_date]."', '".$data['vendor_name']."', '".$data['vendor_code']."', '".$data['no_of_beacons']."', '".$data['beacon_series']."', '".$data['uuid']."' )";
		$query=$this->db->query($sql);
		 if($this->db->affected_rows()>0)
        {
                return $this->db->insert_id();
        }
        else
        {
                return FALSE;
        }
	}
	
	/* To get the setup list */
		
	function get_master_setup_list()
	{
			$sql="SELECT * FROM `AZ_BEACON_MASTER` ";
			$query=$this->db->query($sql);
			if($query->num_rows >0)
			{
				return $query->result();
			}
			else
			{
				return false;
			}
			
			
	}
}

?>

