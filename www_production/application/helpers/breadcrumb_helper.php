<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------

/**
 * CodeIgniter Breadcrumb Helpers
 *
 * @package		CodeIgniter
 * @subpackage		Helpers
 * @category		Helpers
 * @author		Dreamajax Technologies
 * @link		http://www.dreamajax.com
 */

// ------------------------------------------------------------------------

/**
 * Form Declaration
 *
 * Creates Breadcrumb for URI
 *
 * @access	public
 * @param	string	the URI segments of the form destination
 * @param	array	a key/value pair of attributes
 * @param	array	a key/value pair hidden data
 * @return	string
 */
if ( ! function_exists('breadcrumb'))
{
	function breadcrumb()
	{
		/* Breadcrumb Setup Start */
		$ci = &get_instance();
		$i=1;
		$uri = $ci->uri->segment($i);
		$link = '';
		while($uri != '' AND $i <= 3){
		$prep_link = '';
		for($j=1; $j<=$i;$j++){
			$prep_link .= $ci->uri->segment($j).'/';
		}
		
		if($i>=3){
			if($i>=3){ //$ci->uri->segment($i+1) == ''
			$link .='<span>'.ucwords(str_replace('_',' ',$ci->uri->segment($i))).'</span>';
			}else{
				$link .='<a href="'.site_url($prep_link).'">'.ucwords(str_replace('_',' ',$ci->uri->segment($i))).'</a>';
			}
		}
		else
		{
			if($ci->uri->segment($i+1) == ''){ //$ci->uri->segment($i+1) == ''
			$link .='<span>'.ucwords(str_replace('_',' ',$ci->uri->segment($i))).'</span>';
			}else{
				$link .='<a href="'.site_url($prep_link).'">'.ucwords(str_replace('_',' ',$ci->uri->segment($i))).'</a>';
			}
		}
		
		
		$i++;
		$uri = $ci->uri->segment($i);
		}
		return $link;
		/* Breadcrumb Setup End */
	}
}


if ( ! function_exists('breadcrumb_home'))
{
	function breadcrumb_home()
	{
		/* Breadcrumb Setup Start */
		$ci = &get_instance();
		$i=1;
		$uri = $ci->uri->segment($i);
		$link = '';
		while($uri != ''){
		$prep_link = '';
		for($j=1; $j<=$i;$j++){
			$prep_link .= $ci->uri->segment($j).'/';
		}
		
		if($ci->uri->segment($i+1) == ''){
			$link .='<a href="'.site_url($prep_link).'">'.ucwords(str_replace('_',' ',$ci->uri->segment($i))).'</a>';
		}else{
			$link .='<a href="'.site_url($prep_link).'">'.ucwords(str_replace('_',' ',$ci->uri->segment($i))).'</a>';
		}
		$i++;
		$uri = $ci->uri->segment($i);
		}
		return $link;
		/* Breadcrumb Setup End */
	}
}

if ( ! function_exists('breadcrumb_banner'))
{
	function breadcrumb_banner($campaign_id)
	{
		/* Breadcrumb Setup Start */
		$bidlink = 'listing/'.$campaign_id;
		$ci = &get_instance();
		$i=1;
		$uri = $ci->uri->segment($i);
		$link = '';
		while($uri != '' AND $i <= 3){
		$prep_link = '';
		for($j=1; $j<=$i;$j++){
			$prep_link .= $ci->uri->segment($j).'/';
		}
		
		if($i>=3){
			if($i>=3){ //$ci->uri->segment($i+1) == ''
			$link .='<span>'.ucwords(str_replace('_',' ',$ci->uri->segment($i))).'</span>';
			}else{
				$link .='<a href="'.site_url($prep_link).'">'.ucwords(str_replace('_',' ',$ci->uri->segment($i))).'</a>';
			}
		}
		else
		{
			//echo $i.'*'.$ci->uri->segment($i).'~';
			if($i==2)
			{
				if($bidlink == ''){ //$ci->uri->segment($i+1) == ''
				$link .='<span>'.ucwords(str_replace('_',' ',$bidlink)).'</span>';
				
				}else{
					$link .='<a href="'.site_url($prep_link.$bidlink).'">'.ucwords(str_replace('_',' ',$ci->uri->segment($i))).'</a>';
				}

				
			}
			else
			{
				if($ci->uri->segment($i+1) == ''){ //$ci->uri->segment($i+1) == ''
				$link .='<span>'.ucwords(str_replace('_',' ',$ci->uri->segment($i))).'</span>';
				}else{
					$link .='<a href="'.site_url($prep_link).'">'.ucwords(str_replace('_',' ',$ci->uri->segment($i))).'</a>';
				}
			}
		}
		
		
		$i++;
		$uri = $ci->uri->segment($i);
		}
		return $link;
		/* Breadcrumb Setup End */
	}
}

/* End of file breadcrumb_helper.php */
/* Location: ./application/helpers/breadcrumb_helper.php */
