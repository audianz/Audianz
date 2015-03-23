<?php
	//Everyone follow these kind of structure for language labels 

	/*  Module : Inventory - Advertiser */

	$lang['label_inventory_advertisers_page_title']             = "Manage Advertisers";
	$lang['label_advertiser_record_not_found']                  = "Advertisers not found.";
    $lang['label_inventory_advertisers_add_form_page_title']    = "Advertiser Info";
    $lang['label_inventory_advertisers_edit_form_page_title']   = "Advertiser Info";
	
	// Add/Edit  Advertiser
	
	$lang['lang_advertiser_enter_cpassword']				= 	"* Please enter Confirm Password";
	$lang['lang_advertiser_password_cpassword_not_match']	= 	"* Password and Confirm Password fields do not match.";
	$lang['lang_advertiser_enter_valid_email']				= 	"* Please enter only one, valid emailid";
	$lang['lang_advertiser_email_already_exists']			= 	"* Email address already exist";
	$lang['lang_advertiser_username_already_exists']		= 	"* Username already exist";
	$lang['lang_advertiser_enter_name']						= 	"* Please Enter Advertiser Name.";
	$lang['lang_advertiser_enter_email']					= 	"* Email address required..";
	$lang['lang_advertiser_enter_username']					= 	"* Please enter username";
	$lang['lang_advertiser_enter_password']					= 	"* Please enter password";
	$lang['lang_advertiser_enter_confirm_password']			= 	"* Please enter confirm password";
	$lang['lang_advertiser_enter_address']					= 	"* Please enter address";
	$lang['lang_advertiser_enter_city']						= 	"* Please enter city";
	$lang['lang_advertiser_enter_state']					= 	"* Please enter state";
	$lang['lang_advertiser_enter_country']					= 	"* Please select country";
	$lang['lang_advertiser_enter_mobile']					= 	"* Please enter mobile number";
	$lang['lang_advertiser_enter_zip_code']					= 	"* Please enter zip code";
	$lang['lang_advertiser_delete']							= 	"Are you sure you want to delete this advertiser?<br/><b>Note:</b> Also Campaigns, Banners and Spend amount will be deleted";
	$lang['lang_advertiser_add_success']					=	"Advertiser account has been created successfully.";
	$lang['lang_advertiser_edit_success']					=	"Advertiser account has been created successfully.";
	$lang['label_advertiser_single_delete_success']			=	"Advertiser has been deleted sucessfully";
	$lang['label_advertiser_multiple_delete_success']		=	"Selected Advertisers has been deleted sucessfully";
	$lang['label_advertiser_trackers_advid_notfound']		=	"Please select any one of the advertisers and then proceed trackers view..";
	$lang['label_add_advertiser']							=	"Add Adverstiser";
	$lang['label_advertiser_name']							=	"Advertiser Name";
	$lang['label_account']									=	"Account";
	$lang['label_trackers']									=	"Trackers";
	$lang['label_campaigns']								=	"Campaigns";
	
	// Add Fund	
	$lang['label_inventory_advertiser_add_fund']			= 	"Add Fund to Advertiser Account";
	$lang['label_inventory_advertiser_amount']				= 	"Amount";
	$lang['label_add_fund'] 								= 	"Add Amount";
	$lang['label_enter_valid_amount'] 						= 	"* Plese enter valid amount";
	$lang['label_advertiser_add_fund_success']				= 	"Amount has been added for ";
	
	// Manage Trackers
	
	$lang['label_inventory_advertisers_trackers_page_title']= 	"Trackers";
	$lang['label_inventory_campaign_trackers_page_title']	= 	"Campaign Trackers";


	$lang['label_add_tracker']								=	"New Tracker";
	$lang['label_edit_tracker']								=	"Modify Tracker Info";
	$lang['label_ignore']									=	"Ignore";
	$lang['label_pending']									=	"Pending";
	$lang['label_approved']									=	"Approved";
	$lang['label_tracker_sno']								=   "S.No";
	$lang['label_tracker_name']								=   "Tracker Name";
	$lang['label_tracker_desc']								=   "Description";
	$lang['label_tracker_status']							=   "Status";
	$lang['label_tracker_status_select']					=   "Select Tracker Status";
	$lang['label_tracker_status_select_tracker_conv_type']	=   "Select Tracker Conversion Type";
	$lang['label_tracker_type']								=   "Type";
	$lang['label_tracker_linked']							=   "Linked";
	$lang['label_tracker_code']								=   "Code";
	$lang['label_trackers_record_not_found']				=   "Trackers does not exists for selected advertiser.";
	$lang['label_campaigns_trackers_record_not_found']				=   "Trackers does not exists for selected Campaign.";
	
	$lang['label_add_tracker_page_title']					= 	"Tracker Info";
	$lang['label_enter_tracker_name']						= 	"* Please enter tracker name";
	$lang['label_tracker_desc']								= 	"Description";
	$lang['label_tracker_append_code']						= 	"Append Code";
	$lang['label_tracker_conversion_type']					= 	"Conversion Type";
	$lang['label_tracker_status']							= 	"Status";
	$lang['label_enter_tracker_desc']						= 	"* Please enter description";
	$lang['label_enter_tracker_status']						= 	"* Please select status";
	$lang['label_enter_tracker_conversion_type']			= 	"* Please select conversion type";
	$lang['label_enter_tracker_append_code']				= 	"* Please enter Append Code";
	$lang['label_enter_all_fields']							= 	"Some fields are missing..";
	$lang['label_advertiser_add_tracker_success']			=	"Tracker details are saved successfully..";
	$lang['label_advertiser_edit_tracker_success']			=	"Tracker details are updated successfully..";
	$lang['label_advertiser_delete_tracker_success']		= 	"Selected Tracker(s) has been deleted successfully.";
	$lang['label_advertiser_single_delete_tracker_success']		= 	"Selected Tracker has been deleted successfully.";
	$lang['lang_tracker_delete']							=	"Are you sure you want to delete this tracker?";
	
	
	// Manage Trackers - Linked Campaigns
	
	$lang['label_inventory_advertisers_trackers_linked_campaigns_page_title']	=	"Manage Linked Campaigns";
	$lang['label_advertiser_trackers_campaigns_tracker_notfound']				=	"Please select any one of the tracker and then proceed linked campaigns view..";
	$lang['label_trackers_campaigns_record_not_found']							=	"Campaings are does not exists for selected advertiser.";	
	$lang['lang_tracker_camapaign_link']										=   "Are you sure you want to link this campaign?";
	$lang['label_advertiser_tracker_link_campaign_success']						=	"Campaigns are linked to selected tracker";
	$lang['label_tracker_campaign_time_settings_page_title']					=	"Linked Campigns - Time Settings";
	$lang['label_advertiser_tracker_link_campaign_time_success']				=	"Campaign's conversion window has been updated successfully.";
	$lang['label_advertiser_tracker_link_campaign_time_fail']					=	"Campaign's conversion window has not been updated.";
	$lang['label_tracker_campaign_conversion']									=	"Conversion Window for";
	$lang['label_days']															=	"Day(s)";
	$lang['label_hours']														=	"Hour(s)";
	$lang['label_minutes']														=	"Minute(s)";
	$lang['label_seconds']														=	"Second(s)";
	$lang['label_view']															=	"View";
	$lang['label_clicks']														=	"Click";
	
	
	//Manage Trackers - Tracker Code
	
	$lang['label_inventory_advertisers_trackers_code_page_title']				=	"Tracker Invocation Code";
	$lang['label_inventory_advertisers_trackers_code_page_c1']					=	"Conversion Window Tracker code for the Tracker :";
		
	/*  Module : Inventory - Campaigns */
		/* User Activities */
	
	   $lang['label_Activities']															=	"Activities";
	   $lang['label_update']															=	"Update";
	   $lang['label_checkbox_title']														=	"Activity";
	   $lang['label_activity_title']														=	"Task Title";
	   $lang['label_activity_record_not_found']											=    "Activity Record Not Found";
	   $lang['label_activity_successfully']											=    "Activity is successfully Updated.";
	    $lang['label_zone_contact']												=	"Contact";
	   $lang['label_zone_id']														=	"Zone id";
	   $lang['label_zone_name']													=	"Zonename";
	   $lang['label_zone_delivery']												=	"Delivery";
	   $lang['label_zone_width']													=   "Width";
	   $lang['label_zone_height']												=   "Height";
	   $lang['label_zone_revenuetype']								   	    =    "Revenuetype";
	   $lang['label_zone_revenue']											    =    "Revenue";
	   $lang['label_zone_sno']												    =	  "S.No";  
	   $lang['label_zone_publisher']											=    "Publisher";
	   $lang['label_zone_email']											        =    "Email";
	   $lang['label_income_day']											        =    "Income of the Day ";
	   $lang['label_income']											            =    "Income";
	   $lang['label_income_accounttype']									=    "Account type";
	   $lang['label_income_userid']											    =    "User Id";
	   $lang['label_income_day']											        =    "Income of the Day ";
	   $lang['label_campaign _id']											    =    "Campaign ID";
	   $lang['label_campaign _name']											=    "Campaigns Name";
  $lang['label_campaign']											=    "Campaigns";
	   $lang['label_campaign _activate_time']								=    "Activate Time";
	   $lang['label_campaign _expire_time']								=    "Expire Time ";
	   $lang['label_banner_id']													=    "Banner ID";
	   $lang['label_banner_description']										=    "Description";
	   $lang['label_banner_url']													=    "URL";
	   $lang['label_advertiser']														=    "Advertiser";
	   $lang['label_copy_rights']													=    "&copy; 2012. DreamAds. All Rights Reserved.";
	   $lang['label_activity_list']													=    "Activity List";
$lang['lang_activity_subject_email']                                                                                          ="Daily Activity";                                                                           
	$lang['label_active_admin_welcome']                                                                                          ="WELCOME ADMIN,";
	$lang['label_approvals']														="Approvals";
$lang['label_approvals_contact']														="Contact";
$lang['label_approvals_username']														="Username";
	/* End of Module Inventory - Advertiser */
	$lang['label_realtime_bid_rate']				="Realtime Bid Rate";
	$lang['label_rtb_bid_rate']				="RTB Bid Rate";
	$lang['label_realtime_bidding_bid_rate']				="Real Time Bidding Bid Rate";
	$lang['label_realtime_bid_rate_add_success']				="RTB Bid Rate has been added successfully.";
	$lang['label_realtime_bid_rate_update_success']				="RTB Bid Rate has been updated successfully.";
	$lang['label_realtime_bidding']				="Real-Time Bidding";
	$lang['label_make_zone_realtime_bidding']				="Yes, I want to make this Zone with Real-Time Bidding";
      
    $lang['Edit_Size_Alert'] = "If you would change size (WxH), you may not link the existing banners with newly created zones. <br> Do you want to update this size ?";
    $lang['Edit_Size_Alert_Title'] = "Mobile Screens Confirmation Alert";
?>
