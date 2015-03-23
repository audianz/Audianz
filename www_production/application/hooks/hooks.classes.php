<?php
/*
| -------------------------------------------------------------------------
| Hooks Classes
| -------------------------------------------------------------------------
| This file lets you define "hooks classes - Profiler Enabler" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
class ProfilerEnabler
{
	function EnableProfiler()
	{
		$CI = &get_instance();
		$CI->output->enable_profiler( config_item('enable_profiling') );
	}
}
/* End of file hooks.classes.php */
/* Location: ./application/hooks/hooks.classes.php */
