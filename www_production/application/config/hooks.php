<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
| 
*/
$hook['post_controller_constructor'][] = array(
				'class'    => 'Set_adnetwork_timezone',
                                'function' => 'Fetch_adnetwork_timezone',
                                'filename' => 'hooks.timezone.classes.php',
                                'filepath' => 'hooks',
                                'params'   => array()
                                );


$hook['post_controller_constructor'][] = array(
                                'class'    => 'ProfilerEnabler',
                                'function' => 'EnableProfiler',
                                'filename' => 'hooks.classes.php',
                                'filepath' => 'hooks',
                                'params'   => array()
                                );

$hook['post_controller_constructor'][] = array(
				'class'    => 'LoginCheck',
                                'function' => 'AdminLoginCheck',
                                'filename' => 'hooks.login.classes.php',
                                'filepath' => 'hooks',
                                'params'   => array()
                                );
                                
$hook['post_controller_constructor'][] = array(
				'class'    => 'get_site_data',
                                'function' => 'get_site_data',
                                'filename' => 'hooks.site.classes.php',
                                'filepath' => 'hooks',
                                'params'   => array()
                                );

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */
