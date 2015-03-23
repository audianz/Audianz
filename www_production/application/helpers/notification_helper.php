<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('get_notify_duration'))
{	
	function get_notify_duration($minute=0,$hour=0,$day=0,$month=0,$year=0){
			if($year>0)
			{
				if($year==1)
				{		
					$ci =& get_instance();
					$year_ago = $ci->lang->line('lang_year_ago');
					return $year.$year_ago;
				}else{
					$ci =& get_instance();
					$years_ago = $ci->lang->line('lang_years_ago');
					return $year.$years_ago;
				}
			}else if($month>0 && $year<=0)
			{
				if($month==1)
				{
					$ci =& get_instance();
					$month_ago = $ci->lang->line('lang_month_ago');
					return $month.$month_ago;
				}else{
					$ci =& get_instance();
					$months_ago = $ci->lang->line('lang_months_ago');
					return $month.$months_ago;
				}
			}else if($day>0 && $month<=0 && $year<=0)
			{
				if($day==1)
				{
					$ci =& get_instance();
					$yesterday = $ci->lang->line('lang_yesterday');
					return $yesterday;
				}else{
					
					$ci =& get_instance();
					$days_ago = $ci->lang->line('lang_days_ago');
					return $day.$days_ago;
				}
			}else if($hour>0 && $day<=0 && $month<=0 && $year<=0)
			{
				if($hour==1)
				{
					$ci =& get_instance();
					$hour_ago = $ci->lang->line('lang_hour_ago');
					return $hour.$hour_ago;
				}else{
					$ci =& get_instance();
					$hours_ago = $ci->lang->line('lang_hours_ago');
					return $hour.$hours_ago;
				}
			}else if($minute>0 && $hour<=0 && $day<=0 && $month<=0 && $year<=0)
			{
				if($minute==1)
				{
					$ci =& get_instance();
					$minute_ago = $ci->lang->line('lang_minute_ago');
					return $minute.$minute_ago;
				}else{
					$ci =& get_instance();
					$minutes_ago = $ci->lang->line('lang_minutes_ago');
					return $minute.$minutes_ago;
				}
			} 
	}
}
