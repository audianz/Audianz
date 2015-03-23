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
class LoginCheck
{
	function AdminLoginCheck()
	{
			/* Login Check */
		admin_login_check();
		
	}
}
/* End of file hooks.classes.php */
/* Location: ./application/hooks/hooks.classes.php */
